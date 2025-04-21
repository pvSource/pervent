<?php

namespace App\Controller;

use App\Repository\ContestRepository;
use App\Service\ContestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    public function __construct(
        private readonly ContestService $contestService
    )
    {
    }

    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        $contests = $this->contestService->getTopContests();
        return $this->render('index.html.twig', [
            'main_contest' => current($contests),
            'contests' => array_slice($contests, 1),
            'controller_name' => 'IndexController',
        ]);
    }
}
