<?php

namespace App\UrlShorterService;

use App\ConfigService\ConfigService;

class ShortUrlGenerator {
    private const ERROR_ID_IS_TOO_BIG = 'error, id is too big';

    private ConfigService $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * @throws ShortUrlGenerateException
     */
    public function generateShortUrlById(int $id): string
    {
        $availableShortUrlChars = $this->configService->getAvailableShortUrlChars();
        $shortUrlLength = $this->configService->getGeneratingShortUrlLength();
        $availableShortUrlCharsCount = count($availableShortUrlChars);
        $shortUrl = '';

        $nextCharPointer = $id;
        while (strlen($shortUrl) < $shortUrlLength) {
            $correctedNextCharPointer = $nextCharPointer % $availableShortUrlCharsCount;
            $shortUrl .= $availableShortUrlChars[$correctedNextCharPointer];
            $nextCharPointer = floor($nextCharPointer / $availableShortUrlCharsCount);
        }
        if ($nextCharPointer > 0) {
            throw new ShortUrlGenerateException(self::ERROR_ID_IS_TOO_BIG);
        }

        return $shortUrl;
    }
}