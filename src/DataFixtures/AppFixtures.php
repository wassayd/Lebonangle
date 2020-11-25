<?php

namespace App\DataFixtures;

use App\Entity\AdminUser;
use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Picture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();

        $category = new Category();
        $category->setName('categorie test');

        for ($i = 0; $i<10; $i++) {
            $user = new AdminUser();
            $user->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setPlainPassword($faker->password)
                ->setRoles(['ROLE_ADMIN'])
            ;

            for ($j = 0; $j<3; $j++) {
                $advert = new Advert();
                $advert->setTitle($faker->title)
                    ->setEmail($faker->email)
                    ->setAuthor($faker->name)
                    ->setCategory($category)
                    ->setState('draft')
                    ->setContent($faker->text)
                    ->setPrice(100000.0)
                ;

                $picture = new Picture();
                $picture->setPath('file'.$j.'.png');
                $picture->setAdvert($advert);

                $manager->persist($advert);
                $manager->persist($picture);
            }

            $manager->persist($user);

        }
        $manager->persist($category);
        $manager->flush();

    }
}
