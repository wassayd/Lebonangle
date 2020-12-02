<?php

namespace App\Controller\api;

use App\Entity\Advert;
use App\Entity\Picture;
use App\Repository\AdvertRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GetPublishedAdverts
{
    private AdvertRepository $advertRepository;
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    public function __construct(AdvertRepository $advertRepository, CategoryRepository $categoryRepository)
    {
        $this->advertRepository = $advertRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function __invoke(Request $request)
    {
        if ($request->get('category_id')){
            return $this->advertRepository->findBy(["state"=>"published","category"=>$request->get('category_id')]);
        }
        return $this->advertRepository->findBy(["state"=>"published"]);
    }
}
