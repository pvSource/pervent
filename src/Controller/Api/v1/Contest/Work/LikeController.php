<?php

namespace App\Controller\Api\v1\Contest\Work;

use App\Entity\Work;
use App\Service\LikeService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/contest/{contestSlug}/work/{workId}/like')]
final class LikeController extends AbstractController
{
    public function __construct(
        private readonly LikeService $likeService
    )
    {

    }

    #[Route('/add', name: 'app_api_v1_contest_work_like_add', methods: ['GET', 'POST'])]
    public function add(
        #[MapEntity(class: Work::class, expr: 'repository.findOneBy({"id": workId})')] Work $work,
    ): Response
    {
        $like = $this->likeService->add(
            user: $this->getUser(),
            work: $work
        );

        return $this->json([
            'likeId' => $like->getId(),
            'workId' => $like->getWork()->getId(),
            'userId' => $like->getAuthor()->getId(),
            'createdAt' => $like->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }
}
