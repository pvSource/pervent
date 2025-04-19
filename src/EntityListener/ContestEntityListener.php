<?php

namespace App\EntityListener;

use App\Entity\Contest;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Contest::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Contest::class)]
class ContestEntityListener
{
    public function __construct(
        private readonly SluggerInterface $slugger,
    )
    {}

    public function prePersist(Contest $contest, LifecycleEventArgs $args): void
    {
        $contest->computeSlug($this->slugger);
    }

    public function preUpdate(Contest $contest, LifecycleEventArgs $args): void
    {
        $contest->computeSlug($this->slugger);
    }

}