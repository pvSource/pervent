<?php

namespace App\Controller\Contest;

use App\Entity\Contest;
use App\Form\ContestType;
use App\Repository\ContestRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contest')]
final class ContestController extends AbstractController
{
    #[Route(name: 'app_contest_index', methods: ['GET'])]
    public function index(
        Request $request,
        ContestRepository $contestRepository
    ): Response
    {
        $offset = max(0, $request->query->getInt('offset', 0));
        $contestPaginator = $contestRepository->getContestPaginator($offset);

        return $this->render('contest/index.html.twig', [
            'contests' => $contestPaginator,
            'previous' => $offset - ContestRepository::CONTESTS_PER_PAGE,
            'next' => min(count($contestPaginator), $offset + ContestRepository::CONTESTS_PER_PAGE)
        ]);
    }

    #[Route(path: '/new', name: 'app_contest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contest = new Contest();
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contest);
            $entityManager->flush();

            return $this->redirectToRoute('app_contest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contest/new.html.twig', [
            'contest' => $contest,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{code}', name: 'app_contest_show', methods: ['GET'])]
    public function show(
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": code})')] $contest,
    ): Response
    {
        $contestWorks = $contest->getWorks();
        return $this->render('contest/show.html.twig', [
            'contest' => $contest,
            'works' => $contestWorks
        ]);
    }

    #[Route(path: '/{code}/edit', name: 'app_contest_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": code})')] $contest,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(ContestType::class, $contest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contest/edit.html.twig', [
            'contest' => $contest,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{code}', name: 'app_contest_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": code})')] $contest,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contest->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($contest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contest_index', [], Response::HTTP_SEE_OTHER);
    }
}
