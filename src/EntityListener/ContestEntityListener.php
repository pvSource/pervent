<?php

namespace App\EntityListener;

use App\Entity\Contest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, entity: Contest::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Contest::class)]
class ContestEntityListener
{
    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly Security $security,
    )
    {}

    public function prePersist(Contest $contest, LifecycleEventArgs $args): void
    {
        //Установка slug
        $contest->computeSlug($this->slugger);

        //Установка текущего пользователя как автора, если он не задан
        if (null === $contest->getAuthor()) {
            $user = $this->security->getUser();
            if ($user instanceof User) {
                $contest->setAuthor($user);
            }
        }

        //Установка createdAt
        $contest->setCreatedAt(new \DateTimeImmutable());
    }

    public function preUpdate(Contest $contest, LifecycleEventArgs $args): void
    {
        $contest->computeSlug($this->slugger);
    }

}