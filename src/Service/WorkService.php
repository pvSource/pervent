<?php

namespace App\Service;

use App\Entity\Contest;
use App\Repository\ContestRepository;
use App\Repository\WorkRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;

final class WorkService
{
    public function __construct(
        private readonly WorkRepository $workRepository,
        private readonly Security $security
    )
    {}
    public function getFilteredWorks(FormInterface $form)
    {
        $filterData = [
            'search' => $form->get('search')->getData(),
            'author' => $form->get('is-author')->getData() ? $this->security->getUser() : null
        ];

        $sortData = [
            'sortBy' => $form->get('sort-by')->getData()
        ];

        $works = $this->workRepository->getList(
            filterData: $filterData,
            sortData: $sortData
        );

        return $works;
    }

    public function getWorksByContest(Contest $contest)
    {
        return $this->workRepository->findBy([
            'contest' => $contest
        ]);
    }

}