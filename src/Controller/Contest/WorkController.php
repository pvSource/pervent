<?php

namespace App\Controller\Contest;

use App\Entity\Contest;
use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contest/{contestCode}/work')]
final class WorkController extends AbstractController
{
    #[Route(name: 'app_contest_work_index', methods: ['GET'])]
    public function index(
        WorkRepository $workRepository,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": contestCode})')] $contest
    ): Response
    {
        $works = $workRepository->findBy([
            'contest' => $contest
        ]);

        return $this->render('contest/work/index.html.twig', [
            'works' => $works,
            'contest' => $contest,
        ]);
    }

    #[Route('/new', name: 'app_contest_work_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": contestCode})')] $contest
    ): Response
    {
        $work = new Work();
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($work);
            $entityManager->flush();

            return $this->redirectToRoute('app_contest_work_index', [
                'contestCode' => $contest->getCode()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contest/work/new.html.twig', [
            'work' => $work,
            'form' => $form,
            'contest' => $contest
        ]);
    }

    #[Route('/{id}', name: 'app_contest_work_show', methods: ['GET'])]
    public function show(
        Work $work,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": contestCode})')] $contest
    ): Response
    {
        return $this->render('contest/work/show.html.twig', [
            'contest' => $contest,
            'work' => $work,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contest_work_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Work $work,
        EntityManagerInterface $entityManager,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": contestCode})')] $contest
    ): Response
    {
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contest_work_index', [
                'contestCode' => $contest->getCode()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('contest/work/edit.html.twig', [
            'contest' => $contest,
            'work' => $work,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contest_work_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Work $work,
        EntityManagerInterface $entityManager,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"code": contestCode})')] $contest
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($work);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_contest_work_index', [
            'contestCode' => $contest->getCode()
        ], Response::HTTP_SEE_OTHER);
    }
}
