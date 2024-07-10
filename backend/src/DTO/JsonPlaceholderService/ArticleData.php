<?php

namespace App\DTO\JsonPlaceholderService;

use Spatie\DataTransferObject\Attributes\MapFrom;
use Spatie\DataTransferObject\DataTransferObject;

class ArticleData extends DataTransferObject
{
    public int $id;
    #[MapFrom("userId")]
    public int $authorId;
    public string $title;
    public string $body;
    public ?bool $viewed = null;

    public function joinAuthor(AuthorData $authorData): ArticleWithAuthor
    {
        return new ArticleWithAuthor(
            id: $this->id,
            userId: $this->authorId,
            title: $this->title,
            body: $this->body,
            author: $authorData->toArray()
        );
    }
}