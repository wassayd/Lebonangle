<?php

namespace App\EventSubscriber;

use App\Entity\Advert;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class AdvertStateSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    /**
     * AdvertStateSubscriber constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function onWorkflowAdvertCompleted(Event $event)
    {
        /** @var Advert $advert */
        $advert = $event->getSubject();
        $this->logger->info("Nouvelle annonce viens d'etre créer : ".$advert->getTitle());
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.advert.completed.publish' => 'onWorkflowAdvertCompleted',
        ];
    }
}
