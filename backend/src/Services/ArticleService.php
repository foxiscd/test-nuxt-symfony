<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\Article\Filter;
use App\Repository\UserRepository;
use App\Repository\ViewedArticleRepository;
use App\DTO\CacheResponseData;
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



    public function getArticlesWithAuthor(Filter $filter): CacheResponseData
    {
        if ($filter->viewed && $filter->userUuid) {
            $articleIds = $this->viewedArticleRepository->getViewedArticleIds($filter->userUuid);
            $filter->setArticleIds($articleIds);
        }

        return $this->jsonPlaceholderService->getArticlesWithAuthor($filter);
    }

    public function getArticle(int $id): CacheResponseData
    {
        return $this->jsonPlaceholderService->getArticle($id);
    }

    public function markArticleAsViewed(int $articleId,string $userUuid): bool
    {
        $this->entityManager->beginTransaction();

        try {
            $user = $this->userRepository->findOneByUuidOrCreate(Uuid::fromBase32($userUuid));
            $this->viewedArticleRepository->saveViewedArticle($articleId, $user->getId());
            $this->entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollback();
            return false;
        }
    }
}