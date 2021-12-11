<?php

namespace Api\CreateShortUrlApi;

use App\Response\ResponseErrorEnum;
use App\ConfigService\ConfigService;
use App\UrlShorterService\UrlShorterService;

class CreateShortUrlApi
{
    private const POST_KEY_URL = 'url';
    private const POST_KEY_CUSTOM_SHORT_URL = 'customShortUrl';

    private ConfigService $configService;
    private UrlShorterService $urlShorterService;

    private string $fullUrl;
    private ?string $customUrl = null;

    public function __construct(UrlShorterService $urlShorterService, ConfigService $configService) {
        $this->configService = $configService;
        $this->urlShorterService = $urlShorterService;
    }

    public function process(): CreateShortUrlApiResponse
    {
        $response = new CreateShortUrlApiResponse();

        if (!$this->collectInputData() || !$this->validateInputData()) {
            $response->setError(ResponseErrorEnum::INVALID_DATA);
            return $response;
        }

        if (!is_null($this->customUrl)) {
            if ($this->urlShorterService->isShortUrlExists($this->customUrl)) {
                $response->setError(CreateShortUrlErrorEnum::CUSTOM_URL_ALREADY_EXISTS);
                return $response;
            }
        }

        $shortUrl = $this->urlShorterService->createShortUrl($this->fullUrl, $this->customUrl);
        $response->setShortUrl($shortUrl);
        return $response;
    }

    private function collectInputData(): bool
    {
        if (!isset($_POST[self::POST_KEY_URL])) {
            return false;
        }
        $this->fullUrl = $_POST[self::POST_KEY_URL];

        if (isset($_POST[self::POST_KEY_CUSTOM_SHORT_URL])) {
            $this->customUrl = $_POST[self::POST_KEY_CUSTOM_SHORT_URL];
        }

        return true;
    }

    private function validateInputData(): bool
    {
        $pregResult = preg_match(
            '/^(https?:\/\/)?([А-яA-z0-9]{2,}[А-яA-z0-9.]+\.[А-яA-z]{2,})([А-я\/\d\w_\-%]+)([\?#][^\/\n]+)?/',
            $this->fullUrl,
            $matches
        );

        if (
            !$pregResult
            || strlen(!$matches[0]) != $this->fullUrl
        ) {
            return false;
        }

        if (
            !is_null($this->customUrl)
            && strlen($this->customUrl) > $this->configService->getMaxCustomUrlLength()
        ) {
            return false;
        }

        return true;
    }
}