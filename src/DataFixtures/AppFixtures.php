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
        $category->setName('Categorie 1');

        $userRoot = new AdminUser();
        $userRoot->setUsername('root')
            ->setEmail('root@root')
            ->setPlainPassword('root')
            ;

        for ($i = 0; $i<10; $i++) {
            $user = new AdminUser();
            $user->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setPlainPassword($faker->password)
                ->setRoles(['ROLE_ADMIN'])
            ;

            for ($j = 0; $j<3; $j++) {
                $advert = new Advert();
                $advert->setTitle(substr($faker->text,0,10))
                    ->setEmail($faker->email)
                    ->setAuthor($faker->name)
                    ->setCategory($category)
                    ->setState('draft')
                    ->setContent($faker->text)
                    ->setPrice(100000.0)
                ;

                $manager->persist($advert);

            }

            $manager->persist($user);

        }
        $manager->persist($category);
        $manager->persist($userRoot);
        $manager->flush();

    }
}
