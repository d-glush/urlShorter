<?php

namespace App\ShortUrlRepository;

class ShortUrlDTO {
    public ?int $id = null;
    public string $shortUrl;
    public string $fullUrl;
    public int $isCustom = 0;

    public function __construct(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        $this->shortUrl = $data['short_url'];
        $this->fullUrl = $data['full_url'];
        if (isset($data['is_custom'])) {
            $this->isCustom = $data['is_custom'];
        }
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

    public function getIsCustom()
    {
        return $this->isCustom;
    }

    public function setIsCustom($isCustom): self
    {
        $this->isCustom = $isCustom;
        return $this;
    }
}