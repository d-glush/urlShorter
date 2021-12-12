<?php

namespace App\UrlShorterService;

use App\DBConnection\DBException;
use App\ShortUrlRepository\ShortUrlDTO;
use App\ShortUrlRepository\ShortUrlObj;
use App\ShortUrlRepository\ShortUrlRepository;

class UrlShorterService {
    private ShortUrlRepository $shortUrlRepository;
    private ShortUrlGenerator $shortUrlGenerator;

    public function __construct(
        ShortUrlRepository $shortUrlRepository,
        ShortUrlGenerator $shortUrlGenerator
    ) {
        $this->shortUrlRepository = $shortUrlRepository;
        $this->shortUrlGenerator = $shortUrlGenerator;
    }

    /**
     * @throws DBException
     */
    public function isShortUrlExists(string $shortUrl): bool
    {
        $result = $this->shortUrlRepository->getShortByShortUrl($shortUrl);
        return !!$result;
    }

    /**
     * @throws DBException
     */
    public function isFullUrlExists(string $fullUrl): bool
    {
        $result = $this->shortUrlRepository->getShortByFullUrl($fullUrl);
        return !!$result;
    }

    /**
     * @return ShortUrlObj|false
     * @throws DBException
     */
    public function getByFullUrl(string $fullUrl)
    {
        return $this->shortUrlRepository->getShortByFullUrl($fullUrl);
    }

    /**
     * @return ShortUrlObj|false
     * @throws DBException
     */
    public function getByShortUrl(string $shortUrl)
    {
        return $this->shortUrlRepository->getShortByShortUrl($shortUrl);
    }

    /**
     * @throws DBException|ShortUrlGenerateException
     */
    public function createShortUrl($fullUrl, $customUrl = null): string
    {
        if ($customUrl) {
            $shortUrlObj = new ShortUrlObj(new ShortUrlDTO([
                'short_url' => $customUrl,
                'full_url' => $fullUrl,
                'is_custom' => 1,
            ]));
            $this->shortUrlRepository->insertShort($shortUrlObj);
            return $customUrl;
        }

        $shortUrlObj = new ShortUrlObj(new ShortUrlDTO([
            'short_url' => '',
            'full_url' => $fullUrl,
        ]));
        $id = $this->shortUrlRepository->insertShort($shortUrlObj);
        $shortUrl = $this->shortUrlGenerator->generateShortUrlById($id);
        $shortUrlObj->setShortUrl($shortUrl);
        $this->shortUrlRepository->updateShort($id, $shortUrlObj);

        return $shortUrl;
    }
}