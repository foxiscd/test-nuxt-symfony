<?php

namespace App\Repository;


use App\Entity\User;
use App\Entity\ViewedArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

class ViewedArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ViewedArticle::class);
    }

    public function saveViewedArticle(int $articleId, int $userId): void
    {
        $viewedArticle = new ViewedArticle();
        $viewedArticle->setArticleId($articleId);
        $viewedArticle->setUserId($userId);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($viewedArticle);

        $entityManager->flush();
    }

    public function getViewedArticleIds(string $userUuid): array
    {
        $qb = $this->createQueryBuilder('va');

        $qb->select('va.article_id')
            ->join('va.user', 'u')
            ->where('u.uuid = :userUuid')
            ->setParameter('userUuid', Uuid::fromString($userUuid)->toBinary());

        return array_column($qb->getQuery()->getResult(), 'article_id');
    }
}