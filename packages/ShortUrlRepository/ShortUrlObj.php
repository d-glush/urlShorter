<?php

namespace App\ShortUrlRepository;

class ShortUrlObj {
    private ?int $id;
    private string $shortUrl;
    private string $fullUrl;
    private bool $isCustom;

    public function __construct(ShortUrlDTO $shortUrlDTO)
    {
        $this->id = $shortUrlDTO->getId();
        $this->shortUrl = $shortUrlDTO->getShortUrl();
        $this->fullUrl = $shortUrlDTO->getFullUrl();
        $this->isCustom = !!$shortUrlDTO->getIsCustom();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getShortUrl(): string
    {
        return $this->shortUrl;
    }

    public function setShortUrl(string $shortUrl): self
    {
        $this->shortUrl = $shortUrl;
        return $this;
    }

    public function getFullUrl(): string
    {
        return $this->fullUrl;
    }

    public function setFullUrl(string $fullUrl): self
    {
        $this->fullUrl = $fullUrl;
        return $this;
    }

    public function getIsCustom(): bool
    {
        return $this->isCustom;
    }

    public function setIsCustom(bool $isCustom): self
    {
        $this->isCustom = $isCustom;
        return $this;
    }
}