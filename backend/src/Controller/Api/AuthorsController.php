<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Services\JsonPlaceholderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/authors', name: 'authors_')]
class AuthorsController extends AbstractController
{
    private const ROUTE_INDEX = 'index';

    public function __construct(public JsonPlaceholderService $jsonPlaceholderService)
    {
    }

    #[Route('/', name: self::ROUTE_INDEX, methods: ['GET'])]
    public function index(): Response
    {
        $authors = $this->jsonPlaceholderService->getAuthors();

        return $this->json($authors);
    }
}
