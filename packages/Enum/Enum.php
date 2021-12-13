<?php

namespace App\Enum;

use phpDocumentor\Reflection\Types\Mixed_;
use UnexpectedValueException;

/**
 * @codeCoverageIgnore
 */
abstract class Enum
{
    public Mixed_ $value;

    public function __construct($value)
    {
        if (isset($this->getPossibleValues()[$value])) {
            $this->value = $value;
        } else {
            throw new UnexpectedValueException();
        }
    }

    public abstract function getPossibleValues(): array;

    public function getValue(): Mixed_
    {
        return $this->value;
    }
}