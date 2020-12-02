<?php

namespace App\EventSubscriber;

use App\Entity\Advert;
use App\Notification\AdvertPublishedNotification;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Workflow\Event\Event;

class AdvertStateSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private EntityManagerInterface $manager;
    /**
     * @var NotifierInterface
     */
    private NotifierInterface $notifier;

    /**
     * AdvertStateSubscriber constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $manager
     * @param NotifierInterface $notifier
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $manager, NotifierInterface $notifier)
    {
        $this->logger = $logger;
        $this->manager = $manager;
        $this->notifier = $notifier;
    }

    public function onWorkflowAdvertPublished(Event $event)
    {
        /** @var Advert $advert */
        $advert = $event->getSubject();
        $advert->setPublishedAt(new \DateTime());
        $this->logger->info("Nouvelle annonce viens d'etre créer : ".$advert->getTitle());
        $this->notifier->send(new AdvertPublishedNotification($advert), ...$this->notifier->getAdminRecipients());
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.advert.completed.publish' => 'onWorkflowAdvertPublished',
        ];
    }
}
