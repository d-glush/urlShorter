<?php

namespace App\Response;

use App\Enum\Enum;

class ResponseErrorEnum extends Enum
{
    public CONST INVALID_METHOD = 'invalid method';
    public CONST INVALID_DATA = 'invalid data';

    public function getPossibleValues(): array
    {
        return [
            ResponseErrorEnum::INVALID_METHOD => 'INVALID_METHOD',
            ResponseErrorEnum::INVALID_DATA => 'INVALID_DATA',
        ];
    }
}