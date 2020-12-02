<?php

namespace App\EventSubscriber\api;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\AdminUser;
use App\Entity\Advert;
use App\Entity\Picture;
use App\Notification\AdvertCreatedNotification;
use App\Notification\AdvertPublishedNotification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Notifier\NotifierInterface;
use Vich\UploaderBundle\Storage\StorageInterface;

final class AdvertCreatedSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $manager;
    private NotifierInterface $notifier;

    public function __construct(EntityManagerInterface $manager, NotifierInterface $notifier)
    {
        $this->manager = $manager;
        $this->notifier = $notifier;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['sendMail', EventPriorities::POST_WRITE],
        ];
    }

    public function sendMail(ViewEvent $event): void
    {
        /** @var Advert $advert */
        $advert = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$advert instanceof Advert || Request::METHOD_POST !== $method) {
            return;
        }

         $this->notifier->send(new AdvertCreatedNotification($advert,$this->manager),...$this->notifier->getAdminRecipients());

    }

}
