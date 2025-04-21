<?php

namespace App\Repository;

use App\Entity\Work;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Work>
 */
class WorkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Work::class);
    }

    public function getList(?array $filterData = [], ?array $sortData = [])
    {
        $queryBuilder = $this->createQueryBuilder('work');

        if (!isset($sortData['sortBy'])) {
            $sortData['sortBy'] = 'createdAt';
        }

        if (!isset($sortData['sortDirection'])) {
            $sortData['sortDirection'] = 'ASC';
        }

        $queryBuilder->orderBy('work.' . $sortData['sortBy'], $sortData['sortDirection']);

        if ($filterData) {
            if (!empty($filterData['search'])) {
                $queryBuilder
                    ->andWhere("work.name LIKE :search OR work.description LIKE :search")
                    ->setParameter('search', '%' . $filterData['search'] . '%')
                ;
            }

            if ($filterData['author']) {
                $queryBuilder
                    ->andWhere("work.author = :author")
                    ->setParameter('author', $filterData['author'])
                ;
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }


}
