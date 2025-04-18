<?php

namespace App\Controller;

use App\Repository\ContestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(
        ContestRepository $contestRepository,
    ): Response
    {
        $contests = $contestRepository->getTopContests();
        return $this->render('index.html.twig', [
            'main_contest' => current($contests),
            'contests' => array_slice($contests, 1),
            'controller_name' => 'IndexController',
        ]);
    }
}
