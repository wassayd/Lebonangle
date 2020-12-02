<?php

namespace App\EventSubscriber;

use App\Entity\AdminUser;
use App\Entity\Advert;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdvertSubscriber implements EventSubscriber
{

    public function prePersist(LifecycleEventArgs $event)
    {
        if (!$event->getObject() instanceof Advert){
            return false;
        }
        /** @var Advert $advert */
        $advert = $event->getObject();
        $advert->setCreatedAt(new \DateTime());
        $advert->setState('draft');
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }

}
