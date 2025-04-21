<?php

namespace App\Service;

use App\Entity\Like;
use App\Entity\User;
use App\Entity\Work;
use App\Repository\LikeRepository;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;

final class LikeService
{
    public function __construct(
        private readonly LikeRepository $likeRepository,
        private readonly EntityManagerInterface $entityManager
    )
    {
    }

    /**
     * Добавление лайка
     * @param User $user
     * @param Work $work
     * @return Like
     */
    public function add(User $user, Work $work): Like
    {
        if ($like = $this->likeRepository->getByUserAndWork($user, $work)) {
            return $like;
        }

        $like = new Like();
        $like->setAuthor($user);
        $like->setWork($work);
        $like->setCreatedAt(new \DateTimeImmutable()); //todo: entity listener

        $this->entityManager->persist($like);
        $this->entityManager->flush();

        return $like;
    }

    /**
     * Удаление лайка
     * @param User $user
     * @param Work $work
     * @return bool
     */
    public function remove(User $user, Work $work): bool
    {
        if ($like = $this->likeRepository->getByUserAndWork($user, $work)) {
            $this->entityManager->remove($like);
            $this->entityManager->flush();
            return true;
        }

        return true;
    }

    /**
     * Получение лайка
     * @param User $user
     * @param Work $work
     * @return Like|null
     */
    public function get(User $user, Work $work): ?Like
    {
        return $this->likeRepository->getByUserAndWork($user, $work);
    }
}