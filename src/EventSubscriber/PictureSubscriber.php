<?php

namespace App\EventSubscriber;

use App\Entity\AdminUser;
use App\Entity\Advert;
use App\Entity\Picture;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PictureSubscriber implements EventSubscriber
{

    public function prePersist(LifecycleEventArgs $event)
    {
        if (!$event->getObject() instanceof Picture){
            return false;
        }
        /** @var Picture $picture */
        $picture = $event->getObject();
        $picture->setCreatedAt(new \DateTime());
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }
}
