<?php


namespace App\Controller\api;


use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreatePictureAction
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(Request $request): Picture
    {
        $uploadedFile = $request->files->get('file');
        $advertId     = (int)$request->get('advert');

        $advert = $this->manager->getRepository(Advert::class)->find($advertId);

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if (!$advert) {
            throw new BadRequestHttpException('"advert id" is required');
        }

        $picture = new Picture();

        $picture->setCreatedAt(new \DateTime());
        $picture->setFile($uploadedFile);
        $picture->setAdvert($advert);
        return $picture;
    }
}
