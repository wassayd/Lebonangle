<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Category;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/admin/advert")
 */
class AdvertController extends AbstractController
{
    /**
     * @Route("/", name="advert_index", methods={"GET"})
     */
    public function index(Request $request, AdvertRepository $advertRepository, PaginatorInterface $paginator): Response
    {
        $adverts = $paginator->paginate(
            $advertRepository->findAll(),
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts
        ]);
    }


    /**
     * @Route("/{id}", name="advert_show", methods={"GET"})
     */
    public function show(Advert $advert, PictureRepository $pictureRepository): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }

    /**
     * @Route("/{id}/change-state/{transition}", name="advert_change_state", methods={"GET"})
     * @param Advert $advert
     * @param string $transition
     * @param WorkflowInterface $advertStateMachine
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function changeState(
        Advert $advert,
        string $transition,
        WorkflowInterface $advertStateMachine,
        EntityManagerInterface $manager
    ): Response {

        if ($advertStateMachine->can($advert, $transition)) {
            $advertStateMachine->apply($advert, $transition);
            $manager->flush();
            $this->addFlash('success', sprintf('"%s" transition applied', $transition));
        } else {
            $this->addFlash(
                'error',
                sprintf('"%s" transition can\'t be applied to comment "%s"', $transition, $advert->getTitle())
            );
        }

        return $this->redirectToRoute('advert_index');
    }

    /**
     * @Route("/category/{id}", name="advert_by_category", methods={"GET"})
     */
    public function listByCategory(Request $request, AdvertRepository $advertRepository, PaginatorInterface $paginator, Category $id): Response
    {
        $adverts = $paginator->paginate(
            $advertRepository->findBy(["category" => $id]),
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts,
            'title' => "Annonce de la categorie ". $id->getName()
        ]);
    }

}
