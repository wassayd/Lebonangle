<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Form\AdminUser1Type;
use App\Form\AdminUserType;
use App\Repository\AdminUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="admin_user_index", methods={"GET"})
     */
    public function index(AdminUserRepository $adminUserRepository): Response
    {
        return $this->render('admin_user/index.html.twig', [
            'admin_users' => $adminUserRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $adminUser = new AdminUser();
        $form = $this->createForm(AdminUserType::class, $adminUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adminUser);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin_user/new.html.twig', [
            'admin_user' => $adminUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
     */
    public function show(AdminUser $adminUser): Response
    {
        return $this->render('admin_user/show.html.twig', [
            'admin_user' => $adminUser,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AdminUser $adminUser, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $form = $this->createForm(AdminUserType::class, $adminUser, [
            'mandatory' => false
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($adminUser->getPlainPassword() !== null){
                $adminUser->setPassword($userPasswordEncoder->encodePassword($adminUser, $adminUser->getPlainPassword()));
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin_user/edit.html.twig', [
            'admin_user' => $adminUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AdminUser $adminUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adminUser->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adminUser);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
