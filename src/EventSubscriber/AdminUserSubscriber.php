<?php

namespace App\EventSubscriber;

use App\Entity\AdminUser;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserSubscriber implements EventSubscriber
{
    private UserPasswordEncoderInterface $userPasswordEncoder;

    /**
     * AdminUserSubscriber constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    public function prePersist(LifecycleEventArgs $event)
    {
        if (!$event->getObject() instanceof AdminUser){
            return false;
        }
        /** @var AdminUser $user */
        $user = $event->getObject();
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword()));
        $user->setRoles(['ROLE_ADMIN']);
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
        ];
    }
}
