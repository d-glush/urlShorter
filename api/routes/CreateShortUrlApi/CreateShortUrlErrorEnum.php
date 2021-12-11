<?php

namespace Api\CreateShortUrlApi;

use App\Enum\Enum;

class CreateShortUrlErrorEnum extends Enum
{
    public CONST CUSTOM_URL_ALREADY_EXISTS = 'custom url already exists';

    public function getPossibleValues(): array
    {
        return [
            CreateShortUrlErrorEnum::CUSTOM_URL_ALREADY_EXISTS => 'CUSTOM_URL_ALREADY_EXISTS',
        ];
    }
}