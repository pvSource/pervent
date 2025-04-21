<?php

namespace App\Service;

use App\Entity\Contest;
use App\Repository\ContestRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ContestService
{
    public function __construct(
        private readonly ContestRepository $contestRepository,
        private readonly Security $security
    )
    {}
    public function getFilteredPage(FormInterface $form, ?int $offset = 0): Paginator
    {
        $filterData = [
            'search' => $form->get('search')->getData(),
            'author' => $form->get('is-author')->getData() ? $this->security->getUser() : null,
            'participant' => $form->get('is-participant')->getData() ? $this->security->getUser(): null,
        ];

        $sortData = [
            'sortBy' => $form->get('sort-by')->getData()
        ];

        $contestPaginator = $this->contestRepository->getContestPaginator(
            offset: $offset,
            filterData: $filterData,
            sortData: $sortData
        );

        return $contestPaginator;
    }

    public function getTopContests()
    {
        return $this->contestRepository->getTopContests();
    }

}