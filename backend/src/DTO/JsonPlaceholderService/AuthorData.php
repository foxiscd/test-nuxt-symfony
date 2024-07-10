<?php

namespace App\DTO\JsonPlaceholderService;

use Spatie\DataTransferObject\DataTransferObject;

class AuthorData extends DataTransferObject
{
    public int $id;
    public string $name;
    public string $username;
    public string $email;
}