<?php

namespace App\Repository;

use App\Entity\Contest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Contest>
 */
class ContestRepository extends ServiceEntityRepository
{
    public const CONTESTS_PER_PAGE = 3;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contest::class);
    }

    public function getContestPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('contest')
            ->orderBy('contest.beginAt', 'DESC')
            ->setMaxResults(self::CONTESTS_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    public function getTopContests(?int $limit = 7)
    {
        $query = $this->createQueryBuilder('contest')
            ->orderBy('contest.beginAt', 'DESC') //todo
            ->setMaxResults($limit)
            ->getQuery();
        return $query->getResult();
    }
}
