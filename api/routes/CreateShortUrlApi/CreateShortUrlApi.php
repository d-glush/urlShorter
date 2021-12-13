<?php

namespace Api\CreateShortUrlApi;

use App\Response\ResponseErrorEnum;
use App\ConfigService\ConfigService;
use App\UrlShorterService\ShortUrlGenerateException;
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

        if (!is_null($this->customUrl) && strlen($this->customUrl) > 0) {
            if ($this->urlShorterService->isShortUrlExists($this->customUrl)) {
                $shortObj = $this->urlShorterService->getByShortUrl($this->customUrl);
                if ($shortObj->getFullUrl() === $this->fullUrl) {
                    $response->setShortUrl($shortObj->getShortUrl());
                    return $response;
                }
                $response->setError(CreateShortUrlErrorEnum::CUSTOM_URL_ALREADY_EXISTS);
                return $response;
            }
        } else {
            if ($this->urlShorterService->isFullUrlExists($this->fullUrl)) {
                $shortObj = $this->urlShorterService->getByFullUrl($this->fullUrl);
                $response->setShortUrl($shortObj->getShortUrl());
                return $response;
            }
        }

        try {
            $shortUrl = $this->urlShorterService->createShortUrl($this->fullUrl, $this->customUrl);
            $response->setShortUrl($shortUrl);
            return $response;
        } catch (ShortUrlGenerateException $e) {
            $response->setError(CreateShortUrlErrorEnum::URLS_OUT_OF_STOCK);
            return $response;
        }
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
        if (!$this->isUrlAvailable($this->fullUrl)) {
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

    public function isUrlAvailable($url): bool
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            return false;
        }

        $curlInit = curl_init($url);
        $msConnectTimeOut = $this->configService->getFullUrlConnectTimeoutTime();
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT_MS, $msConnectTimeOut);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

        $response = curl_exec($curlInit);
        curl_close($curlInit);
        return (bool)$response;
    }
}