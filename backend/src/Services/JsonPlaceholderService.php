<?php

namespace App\Services;

use App\DTO\Article\Filter;
use App\DTO\CacheResponseData;
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
     * @return CacheResponseData
     */
    public function getArticles(Filter $filter): CacheResponseData
    {
        $cachePostfix = 'all';

        if ($filter->viewed) {
            $cachePostfix = 'viewed';
        } elseif ($filter->author_id) {
            $cachePostfix = $filter->author_id;
        }

        $cacheKey = "articles_{$cachePostfix}";

        return $this->makeCacheResponse($cacheKey, function (ItemInterface $item) use ($filter) {
            $item->expiresAfter(120);

            $url = 'https://jsonplaceholder.typicode.com/posts';
            $url .= $filter->author_id ? "?userId=$filter->author_id" : null;

            $articles = $this->client->request('GET', $url)->toArray();

            $filteredArticles = array_filter($articles, function ($article) use ($filter) {
                return !isset($filter->article_ids) || in_array($article['id'], $filter->article_ids);
            });

            return array_map(function ($article) {
                return new ArticleData($article);
            }, $filteredArticles);
        });
    }

    /**
     * @param Filter $filter
     * @return CacheResponseData
     */
    public function getArticlesWithAuthor(Filter $filter): CacheResponseData
    {
        $cacheKey = 'articlesWithAuthors_' . ($filter->author_id ?? 'all');

        return $this->makeCacheResponse($cacheKey, function (ItemInterface $item) use ($filter) {
            $item->expiresAfter(120);

            return $this->involveAuthorsIntoArticles(
                $this->getArticles($filter)->data,
                $this->getAuthors()->data
            );
        });
    }

    /**
     * @return CacheResponseData
     */
    public function getAuthors(): CacheResponseData
    {
        $cacheKey = 'authors_all';

        return $this->makeCacheResponse($cacheKey, function (ItemInterface $item) {
            $item->expiresAfter(300);

            $response = $this->client->request('GET', 'https://jsonplaceholder.typicode.com/users');

            return array_map(fn($author) => new AuthorData($author), $response->toArray());
        });
    }

    /**
     * @param int $authorId
     * @return CacheResponseData
     */
    public function getAuthor(int $authorId): CacheResponseData
    {
        $cacheKey = 'author_' . $authorId;

        return $this->makeCacheResponse($cacheKey, function (ItemInterface $item) use ($authorId) {
            $item->expiresAfter(300);

            $response = $this->client->request('GET', "https://jsonplaceholder.typicode.com/users/$authorId");

            return new AuthorData($response->toArray());
        });
    }

    /**
     * @param int $id
     * @return CacheResponseData
     */
    public function getArticle(int $id): CacheResponseData
    {
        $cacheKey = 'article_' . $id;

        return $this->makeCacheResponse($cacheKey, function (ItemInterface $item) use ($id) {
            $item->expiresAfter(300);

            $response = $this->client->request('GET', "https://jsonplaceholder.typicode.com/posts/$id");
            $article = new ArticleData($response->toArray());

            return $article->joinAuthor($this->getAuthor($article->authorId)->data);
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

    /**
     * @param string $cacheKey
     * @return string|null
     */
    protected function getEtag(string $cacheKey): ?string
    {
        $item = $this->cache->getItem($cacheKey);

        if (!$item->isHit()) {
            return null;
        }

        return $item->getMetadata()['expiry'] ?? null;
    }

    /**
     * @param string $cacheKey
     * @param callable $callback
     * @return CacheResponseData
     */
    protected function makeCacheResponse(string $cacheKey, callable $callback): CacheResponseData
    {
        $data = $this->cache->get($cacheKey, $callback);
        $expiredAt = $this->getEtag($cacheKey);

        return new CacheResponseData(expiredAt: $expiredAt, data: $data);
    }
}