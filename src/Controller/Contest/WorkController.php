<?php

namespace App\Controller\Contest;

use App\Entity\Contest;
use App\Entity\Work;
use App\Form\WorkType;
use App\Repository\WorkRepository;
use App\Service\ImageService;
use App\Service\WorkService;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/contest/{contestSlug}/work')]
final class WorkController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface            $entityManager,
        private readonly WorkService                       $workService,
        private readonly ImageService $imageService,
    ) {}

    #[Route(name: 'app_contest_work_index', methods: ['GET'])]
    public function index(
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": contestSlug})')] $contest,
    ): Response
    {
        $works = $this->workService->getWorksByContest($contest);

        return $this->render('contest/work/index.html.twig', [
            'works' => $works,
            'contest' => $contest,
        ]);
    }

    /**
     * @throws RandomException
     */
    #[Route('/new', name: 'app_contest_work_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": contestSlug})')] $contest
    ): Response
    {
        $work = new Work();
        $work->setContest($contest);
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($image = $form['image']->getData()) {
                $filename = $this->imageService->upload($image);
                $work->setImagePath($filename);
            }

            $this->entityManager->persist($work);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contest_work_index', [
                'contestSlug' => $contest->getSlug()
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
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": contestSlug})')] $contest
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
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": contestSlug})')] $contest
    ): Response
    {
        $form = $this->createForm(WorkType::class, $work);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contest_work_index', [
                'contestSlug' => $contest->getSlug()
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
        #[MapEntity(class: Contest::class, expr: 'repository.findOneBy({"slug": contestSlug})')] $contest
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$work->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($work);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_contest_work_index', [
            'contestSlug' => $contest->getSlug()
        ], Response::HTTP_SEE_OTHER);
    }
}
