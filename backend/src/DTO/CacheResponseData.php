<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class CacheResponseData extends DataTransferObject
{
    public string $expiredAt;
    public mixed $data;
}