<?php

namespace App\Response;

/**
 * @codeCoverageIgnore
 */
class Response
{
    public ?string $error = null;
    public bool $isError = false;

    public function getError(): ?string
    {
        return $this->error;
    }

    public function setError(string $error): self
    {
        $this->error = $error;
        $this->isError = true;
        return $this;
    }
}