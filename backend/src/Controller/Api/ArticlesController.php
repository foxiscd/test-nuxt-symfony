<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\Article\Filter;
use App\Exception\ValidateException;
use App\Request\ArticleRequest;
use App\Services\ArticleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/articles', name: 'articles_')]
class ArticlesController extends AbstractController
{
    private const ROUTE_INDEX = 'index';
    private const ROUTE_DETAILS = 'show';
    private const ROUTE_VIEWED = 'viewed';

    public function __construct(RequestStack $request, public ArticleService $articleService)
    {
    }

    #[Route('/', name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(Request $request, ArticleRequest $articleRequest): Response
    {
        try {
            $filter = new Filter($articleRequest->validate($request));
            $filter->userUuid = $request->cookies->get('USER_UUID');
        } catch (ValidateException $e) {
            return $this->json($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        $articles = $this->articleService->getArticlesWithAuthor($filter);

        return $this->json($articles);
    }

    #[Route('/{id}', name: self::ROUTE_DETAILS, methods: ['GET'])]
    public function show(int $id): Response
    {
        $article = $this->articleService->getArticle($id);

        return $this->json($article);
    }


    #[Route('/{id}/view', name: self::ROUTE_VIEWED, methods: ['POST'])]
    public function view(Request $request, int $id): Response
    {
        $ok = $this->articleService->markArticleAsViewed($id, $request->cookies->get('USER_UUID'));

        return $this->json($ok);
    }
}
