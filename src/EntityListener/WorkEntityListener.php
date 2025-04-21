<?php

namespace App\EntityListener;

use App\Entity\Contest;
use App\Entity\User;
use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Work::class)]
class WorkEntityListener
{
    public function __construct(
        private readonly Security $security,
    )
    {}

    public function prePersist(Work $work, LifecycleEventArgs $args): void
    {

        //Установка текущего пользователя как автора, если он не задан
        if (null === $work->getAuthor()) {
            $user = $this->security->getUser();
            if ($user instanceof User) {
                $work->setAuthor($user);
            }
        }

        //Установка createdAt
        $work->setCreatedAt(new \DateTimeImmutable());
    }
}