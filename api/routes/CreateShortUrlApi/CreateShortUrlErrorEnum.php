<?php

namespace Api\CreateShortUrlApi;

use App\Enum\Enum;

/**
 * @codeCoverageIgnore
 */
class CreateShortUrlErrorEnum extends Enum
{
    public CONST CUSTOM_URL_ALREADY_EXISTS = 'custom url already exists';
    public CONST URLS_OUT_OF_STOCK = 'available urls out of stock, please retry later';

    public function getPossibleValues(): array
    {
        return [
            CreateShortUrlErrorEnum::CUSTOM_URL_ALREADY_EXISTS => 'CUSTOM_URL_ALREADY_EXISTS',
            CreateShortUrlErrorEnum::URLS_OUT_OF_STOCK => 'URLS_OUT_OF_STOCK',
        ];
    }
}