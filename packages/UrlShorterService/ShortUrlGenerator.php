<?php

namespace App\UrlShorterService;

use App\ConfigService\ConfigService;

class ShortUrlGenerator {
    private const ERROR_ID_IS_TOO_BIG = 'error, id is too big';

    private ConfigService $configService;

    private array $availableShortUrlChars;
    private int $shortUrlLength;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
        $this->availableShortUrlChars = $this->configService->getAvailableShortUrlChars();
        $this->shortUrlLength = $this->configService->getGeneratingShortUrlLength();
    }

    /**
     * @throws ShortUrlGenerateException
     */
    public function generateShortUrlById(int $id): string
    {
        $availableShortUrlCharsCount = count($this->availableShortUrlChars);
        $shortUrl = '';

        $nextCharPointer = $id;
        while (strlen($shortUrl) < $this->shortUrlLength) {
            $correctedNextCharPointer = $nextCharPointer % $availableShortUrlCharsCount;
            $shortUrl .= $this->availableShortUrlChars[$correctedNextCharPointer];
            $nextCharPointer = floor($nextCharPointer / $availableShortUrlCharsCount);
        }
        if ($nextCharPointer > 0) {
            throw new ShortUrlGenerateException(self::ERROR_ID_IS_TOO_BIG);
        }

        return $shortUrl;
    }
}