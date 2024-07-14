<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Article\Filter;
use App\DTO\JsonPlaceholderService\ArticleData;
use App\Repository\UserRepository;
use App\Repository\ViewedArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class ArticleService
{
    public function __construct(
        private JsonPlaceholderService  $jsonPlaceholderService,
        private UserRepository          $userRepository,
        private ViewedArticleRepository $viewedArticleRepository,
        private EntityManagerInterface  $entityManager,
    )
    {
    }



    public function getArticlesWithAuthor(Filter $filter): array
    {
        if ($filter->viewed && $filter->userUuid) {
            $articleIds = $this->viewedArticleRepository->getViewedArticleIds($filter->userUuid);
            $filter->setArticleIds($articleIds);
        }

        return $this->jsonPlaceholderService->getArticlesWithAuthor($filter);
    }

    public function getArticle(int $id): ArticleData
    {
        return $this->jsonPlaceholderService->getArticle($id);
    }

    public function markArticleAsViewed(int $articleId,string $userUuid): bool
    {
        $this->entityManager->beginTransaction();

        try {
            $user = $this->userRepository->findOneByUuidOrCreate(Uuid::fromString($userUuid));
            $this->viewedArticleRepository->saveViewedArticle($articleId, $user);
            $this->entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return false;
        }
    }
}