<?php

namespace App\Controller\Contest;

use App\Entity\Contest;
use App\Form\ContestType;
use App\Form\Filter\ContestListType;
use App\Repository\ContestRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Random\RandomException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contest')]
final class ContestController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private readonly ContestRepository $contestRepository,
        #[Autowire('%image_dir%')] private readonly string $imageDir,
    )
    {

    }
    #[Route(name: 'app_contest_index', methods: ['GET'])]
    public function index(
        Request $request
    ): Response
    {
        $form = $this->createForm(ContestListType::class);
        $form->handleRequest($request);

        $filterData = [
            'search' => $form->get('search')->getData(),
            'author' => $form->get('is-author')->getData() ? $this->getUser() : null,
            'participant' => $form->get('is-participant')->getData() ? $this->getUser(): null,
        ];

        $sortData = [
            'sortBy' => $form->get('sort-by')->getData()
        ];

        $offset = max(0, $request->query->getInt('offset', 0));
        $contestPaginator = $this->contestRepository->getContestPaginator(
            offset: $offset,
            filterData: $filterData,
            sortData: $sortData
        );

        return $this->render('contest/index.html.twig', [
            'contests' => $contestPaginator,
            'filterForm' => $form->createView(),
            'previous' => $offset - ContestRepository::CONTESTS_PER_PAGE,
            'next' => min(count($contestPaginator), $offset + ContestRepository::CONTESTS_PER_PAGE)
        ]);
    }

    /**
     * @throws RandomException
     */
    #[Route(path: '/new', name: 'app_contest_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $contest = new Contest();
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($image = $form['image']->getData()) {
                $filename = bin2hex(random_bytes(8)) . '.' . $image->guessExtension();
                $image->move($this->imageDir, $filename);
                $contest->setImagePath($filename);
            }

            $this->entityManager->persist($contest);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contest/new.html.twig', [
            'contest' => $contest,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{slug}', name: 'app_contest_show', methods: ['GET'])]
    public function show(
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": slug})')] $contest,
    ): Response
    {
        $contestWorks = $contest->getWorks();
        return $this->render('contest/show.html.twig', [
            'contest' => $contest,
            'works' => $contestWorks
        ]);
    }

    #[Route(path: '/{slug}/edit', name: 'app_contest_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": slug})')] $contest
    ): Response
    {
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contest/edit.html.twig', [
            'contest' => $contest,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{slug}', name: 'app_contest_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": slug})')] $contest,
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contest->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($contest);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_contest_index', [], Response::HTTP_SEE_OTHER);
    }
}
