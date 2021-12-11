<?php

namespace Api\CreateShortUrlApi;

use App\Response\Response;

class CreateShortUrlApiResponse extends Response
{
    public string $shortUrl;

    public function __construct(string $shortUrl = '')
    {
        $this->shortUrl = $shortUrl;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): void
    {
        $this->shortUrl = $shortUrl;
    }
}