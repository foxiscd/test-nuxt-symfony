<?php

namespace App\DTO\Article;

use Spatie\DataTransferObject\DataTransferObject;

class Filter extends DataTransferObject
{
    public ?int $author_id = null;
    public ?bool $viewed = null;
    public ?string $userUuid = null;
    public ?array $article_ids = null;

    public function setArticleIds(array $ids): void
    {
        $this->article_ids = $ids;
    }
}