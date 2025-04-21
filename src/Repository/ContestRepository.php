<?php

namespace App\Repository;

use App\Entity\Contest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * @extends ServiceEntityRepository<Contest>
 */
class ContestRepository extends ServiceEntityRepository
{
    public const CONTESTS_PER_PAGE = 9;
    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Contest::class);
    }

    public function getContestPaginator(
        int $offset,
        ?array $filterData = [],
        ?array $sortData = [],
    ): Paginator
    {
        $queryBuilder = $this->createQueryBuilder('contest')
            ->setMaxResults(self::CONTESTS_PER_PAGE)
            ->setFirstResult($offset);

        if (!isset($sortData['sortBy'])) {
            $sortData['sortBy'] = 'beginAt';
        }

        if (!isset($sortData['sortDirection'])) {
            $sortData['sortDirection'] = 'ASC';
        }

        $queryBuilder->orderBy('contest.' . $sortData['sortBy'], $sortData['sortDirection']);

        if ($filterData) {
            if (!empty($filterData['search'])) {
                $queryBuilder
                    ->andWhere("contest.name LIKE :search OR contest.description LIKE :search")
                    ->setParameter('search', '%' . $filterData['search'] . '%')
                ;
            }

            if ($filterData['author']) {
                $queryBuilder
                    ->andWhere("contest.author = :author")
                    ->setParameter('author', $filterData['author'])
                ;
            }

            if ($filterData['participant']) {
                $queryBuilder
                    ->join('contest.works', 'work')
                    ->andWhere("work.author = :participant")
                    ->setParameter('participant', $filterData['participant'])
                ;
            }
        }

        $query = $queryBuilder->getQuery();
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
