<?php

namespace App\Services;

use App\DTO\Article\Filter;
use App\DTO\JsonPlaceholderService\ArticleData;
use App\DTO\JsonPlaceholderService\ArticleWithAuthor;
use App\DTO\JsonPlaceholderService\AuthorData;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class JsonPlaceholderService
{

    public function __construct(
        protected HttpClientInterface $client,
        protected CacheInterface      $cache
    )
    {
    }


    /**
     * @param Filter $filter
     * @return ArticleData[]
     */
    public function getArticles(Filter $filter): array
    {
        $cacheKey = $filter->author_id ?  'articles_{$filter->author_id}' : 'articles_all';

        $articles = $this->cache->get($cacheKey, function (ItemInterface $item) use ($filter) {
            $item->expiresAfter(120);

            $url = 'https://jsonplaceholder.typicode.com/posts';
            $url .= $filter->author_id ? "?userId=$filter->author_id" : null;

            $articles = $this->client->request('GET', $url)->toArray();

            return array_map(function ($article) {
                return new ArticleData($article);
            }, $articles);
        });

        return $this->filterArticles($articles ?? [], $filter);
    }

    /**
     * @param ArticleData[] $articles
     * @param Filter $filter
     * @return array
     */
    public function filterArticles(array $articles, Filter $filter): array
    {
        return array_filter($articles, function ($article) use ($filter) {
            if (isset($filter->article_ids)) {
                return in_array($article->id, $filter->article_ids) ? $article : null;
            } else {
                return $article;
            }
        });
    }

    /**
     * @param Filter $filter
     * @return ArticleWithAuthor[]
     */
    public function getArticlesWithAuthor(Filter $filter): array
    {
        return $this->involveAuthorsIntoArticles(
            $this->getArticles($filter),
            $this->getAuthors()
        );
    }

    /**
     * @return AuthorData[]
     */
    public function getAuthors(): array
    {
        $cacheKey = 'authors_all';

        return $this->cache->get($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(300);

            $response = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/users');

            return array_map(fn($author) => new AuthorData($author), $response->toArray());
        });
    }

    /**
     * @param int $authorId
     * @return AuthorData
     */
    public function getAuthor(int $authorId): AuthorData
    {
        $cacheKey = 'author_' . $authorId;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($authorId) {
            $item->expiresAfter(300);

            $response = $this->client->request('GET', "https://jsonplaceholder.typicode.com/users/$authorId");

            return new AuthorData($response->toArray());
        });
    }

    /**
     * @param int $id
     * @return ArticleData
     */
    public function getArticle(int $id): ArticleData
    {
        $cacheKey = 'article_' . $id;

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(300);

            $response = $this->client->request('GET', "https://jsonplaceholder.typicode.com/posts/$id");
            $article = new ArticleData($response->toArray());

            return $article->joinAuthor($this->getAuthor($article->authorId));
        });
    }

    /**
     * @param ArticleData[] $articles
     * @param AuthorData[] $authors
     * @return ArticleWithAuthor[]
     */
    protected function involveAuthorsIntoArticles(array $articles, array $authors): array
    {
        $authorsMap = [];
        foreach ($authors as $author) {
            $authorsMap[$author->id] = $author;
        }

        $articlesWithAuthors = [];
        foreach ($articles as $article) {
            $articlesWithAuthors[] = $article->joinAuthor($authorsMap[$article->authorId]);
        }

        return $articlesWithAuthors;
    }
}