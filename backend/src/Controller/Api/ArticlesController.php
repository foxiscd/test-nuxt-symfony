<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/articles', name: 'articles_')]
class ArticlesController extends AbstractController
{
    #[Route('/', name: 'get', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json([
            [
                'id' => 1,
                'userId' => 1,
                'viewed' => true,
                'title' => 'Title of the story',
                'body' => 'This is a text of article and you can put here something you want to see in the future',
                'author' => [
                    'id' => 1,
                    'name' => 'John Doe',
                    'username' => 'John',
                    'email' => 'john@example.com',
                ],
            ],
            [
                'id' => 2,
                'userId' => 2,
                'viewed' => false,
                'title' => 'Title of the story 2',
                'body' => 'This is a text of article and you can put here something you want to see in the future 2',
                'author' => [
                    'id' => 2,
                    'name' => 'Rag Fal',
                    'username' => 'Rag',
                    'email' => 'rag@example.com',
                ],
            ],
            [
                'id' => 3,
                'userId' => 2,
                'viewed' => true,
                'title' => 'Title of the ',
                'body' => 'This is a text of article and yo',
                'author' => [
                    'id' => 2,
                    'name' => 'Rag Fal',
                    'username' => 'Rag',
                    'email' => 'rag@example.com',
                ],
            ],
        ]);
    }
}
