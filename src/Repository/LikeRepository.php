<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function getByUserAndWork(User $user, Work $work): ?Like
    {
        $queryBuilder = $this->createQueryBuilder('l');
        $queryBuilder
            ->andWhere('l.author = :author')->setParameter('author', $user)
            ->andWhere('l.work = :work')->setParameter('work', $work)
            ->setMaxResults(1);
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
