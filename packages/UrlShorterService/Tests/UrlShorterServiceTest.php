<?php

namespace App\UrlShorterService\Tests;

use App\ShortUrlRepository\ShortUrlDTO;
use App\ShortUrlRepository\ShortUrlObj;
use App\ShortUrlRepository\ShortUrlRepository;
use App\UrlShorterService\ShortUrlGenerator;
use App\UrlShorterService\UrlShorterService;
use PHPUnit\Framework\TestCase;

class UrlShorterServiceTest extends TestCase
{
    protected ShortUrlRepository $shortUrlRepository;
    protected ShortUrlGenerator $shortUrlGenerator;
    protected array $availableUrlChars;
    protected int $urlLength;

    protected function setUp(): void
    {
        $this->shortUrlRepository = $this->createMock(ShortUrlRepository::class);
        $this->shortUrlGenerator = $this->createMock(ShortUrlGenerator::class);
    }


    public function isShortUrlExistsProvider(): array
    {
        return [
            'short exists' => [
                'nomatter1',
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://google.com',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ])),
                true
            ],
            'short not exists' => [
                'nomatter2',
                false,
                false
            ],
        ];
    }

    /**
     * @covers \App\UrlShorterService\UrlShorterService::isShortUrlExists
     * @covers \App\UrlShorterService\UrlShorterService::__construct
     * @dataProvider isShortUrlExistsProvider
     */
    public function testIsShortUrlExists($shortUrl, $getShortByShortUrlResult, bool $expectedResult): void
    {
        $urlShorterService = new UrlShorterService($this->shortUrlRepository, $this->shortUrlGenerator);

        $this->shortUrlRepository
            ->expects($this->once())
            ->method('getShortByShortUrl')
            ->with($shortUrl)
            ->will($this->returnValue($getShortByShortUrlResult));

        $this->assertEquals($expectedResult, $urlShorterService->isShortUrlExists($shortUrl));
    }


    public function isFullUrlExistsProvider(): array
    {
        return [
            'full exists' => [
                'https://nomatt.er',
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ])),
                true
            ],
            'full not exists' => [
                'https://nomatt.er2',
                false,
                false
            ],
        ];
    }

    /**
     * @covers \App\UrlShorterService\UrlShorterService::isFullUrlExists
     * @dataProvider isFullUrlExistsProvider
     */
    public function testIsFullUrlExists($fullUrl, $getShortByShortUrlResult, bool $expectedResult): void
    {
        $urlShorterService = new UrlShorterService($this->shortUrlRepository, $this->shortUrlGenerator);

        $this->shortUrlRepository
            ->expects($this->once())
            ->method('getShortByFullUrl')
            ->with($fullUrl)
            ->will($this->returnValue($getShortByShortUrlResult));

        $this->assertEquals($expectedResult, $urlShorterService->isFullUrlExists($fullUrl));
    }


    public function getByShortUrlProvider(): array
    {
        return [
            'exists' => [
                'aavas2',
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ])),
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ])),
            ],
            'not exists' => [
                'aavas1',
                false,
                false
            ],
        ];
    }

    /**
     * @covers \App\UrlShorterService\UrlShorterService::getByShortUrl
     * @dataProvider getByShortUrlProvider
     */
    public function testGetByShortUrl($shortUrl, $repoGetResult, $expectedResult): void
    {
        $urlShorterService = new UrlShorterService($this->shortUrlRepository, $this->shortUrlGenerator);

        $this->shortUrlRepository
            ->expects($this->once())
            ->method('getShortByShortUrl')
            ->with($shortUrl)
            ->will($this->returnValue($repoGetResult));

        $this->assertEquals($expectedResult, $urlShorterService->getByShortUrl($shortUrl));
    }


    public function getByFullUrlProvider(): array
    {
        return [
            'exists' => [
                'https://nomatt.er',
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ])),
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ])),
            ],
            'not exists' => [
                'https://nomatt.er2',
                false,
                false
            ],
        ];
    }

    /**
     * @covers \App\UrlShorterService\UrlShorterService::getByFullUrl
     * @dataProvider getByShortUrlProvider
     */
    public function testGetByFullUrl($fullUrl, $repoGetResult, $expectedResult): void
    {
        $urlShorterService = new UrlShorterService($this->shortUrlRepository, $this->shortUrlGenerator);

        $this->shortUrlRepository
            ->expects($this->once())
            ->method('getShortByFullUrl')
            ->with($fullUrl)
            ->will($this->returnValue($repoGetResult));

        $this->assertEquals($expectedResult, $urlShorterService->getByFullUrl($fullUrl));
    }


    public function createShortUrlProvider(): array
    {
        return [
            'not custom' => [
                'https://nomatt.er2',
                null,
                3,
                'shorturl'
            ],
            'custom' => [
                'https://nomatt.er',
                '123sad',
                null,
                null,
            ],
        ];
    }

    /**
     * @covers \App\UrlShorterService\UrlShorterService::createShortUrl
     * @dataProvider createShortUrlProvider
     */
    public function testCreateShortUrl($fullUrl, $customUrl, $insertResult, $generatedShortUrl): void
    {
        $urlShorterService = new UrlShorterService($this->shortUrlRepository, $this->shortUrlGenerator);

        if ($customUrl) {
            $expectedShortObj = new ShortUrlObj(new ShortUrlDTO([
                'short_url' => $customUrl,
                'full_url' => $fullUrl,
                'is_custom' => 1,
            ]));

            $this->shortUrlRepository
                ->expects($this->once())
                ->method('insertShort')
                ->with($expectedShortObj);

            $this->assertEquals($customUrl, $urlShorterService->createShortUrl($fullUrl, $customUrl));
        } else {
            $expectedShortObj = new ShortUrlObj(new ShortUrlDTO([
                'short_url' => '',
                'full_url' => $fullUrl,
                'is_custom' => 0,
            ]));

            $this->shortUrlRepository
                ->expects($this->once())
                ->method('insertShort')
//                ->with($expectedShortObj)
                ->will($this->returnValue($insertResult));

            $this->shortUrlGenerator
                ->expects($this->once())
                ->method('generateShortUrlById')
                ->with($insertResult)
                ->will($this->returnValue($generatedShortUrl));

            $expectedShortObj->setShortUrl($generatedShortUrl);

            $this->shortUrlRepository
                ->expects($this->once())
                ->method('updateShort')
                ->with($insertResult, $expectedShortObj);

            $this->assertEquals($generatedShortUrl, $urlShorterService->createShortUrl($fullUrl, $customUrl));
        }

    }
}