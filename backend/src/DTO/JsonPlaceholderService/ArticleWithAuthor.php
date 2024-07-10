<?php

namespace App\DTO\JsonPlaceholderService;

use App\DTO\JsonPlaceholderService\Casts\AuthorCast;
use Spatie\DataTransferObject\Attributes\CastWith;

class ArticleWithAuthor extends ArticleData
{
    public AuthorData $author;
}