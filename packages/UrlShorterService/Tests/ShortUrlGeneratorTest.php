<?php

namespace App\UrlShorterService\Tests;

use App\ConfigService\ConfigService;
use App\UrlShorterService\ShortUrlGenerateException;
use App\UrlShorterService\ShortUrlGenerator;
use PHPUnit\Framework\TestCase;

class ShortUrlGeneratorTest extends TestCase
{
    protected ShortUrlGenerator $shortUrlGenerator;
    protected ConfigService $configService;
    protected array $availableUrlChars;
    protected int $urlLength;

    protected function setUp(): void
    {
        $this->configService = $this->createMock(ConfigService::class);
        $this->availableUrlChars = [
            'a', 'b', 'c'
        ];
        $this->urlLength = 3;
    }

    /**
     * @covers App\UrlShorterService\ShortUrlGenerator::generateShortUrlById
     * @covers App\UrlShorterService\ShortUrlGenerator::__construct
     * @dataProvider generatorProvider
     */
    public function testGenerateShortUrlById(int $id, string $expectedShortUrl): void
    {
        $this->shortUrlGenerator = new ShortUrlGenerator($this->configService);

        $this->configService
            ->expects($this->once())
            ->method('getAvailableShortUrlChars')
            ->will($this->returnValue($this->availableUrlChars));
        $this->configService
            ->expects($this->once())
            ->method('getGeneratingShortUrlLength')
            ->will($this->returnValue($this->urlLength));
        if ($id > pow($this->urlLength, count($this->availableUrlChars)) - 1) {
            $this->expectException(ShortUrlGenerateException::class);
        }
        $this->assertEquals($expectedShortUrl, $this->shortUrlGenerator->generateShortUrlById($id));
    }

    public function generatorProvider()
    {
        return [
            'min id' => [0, 'aaa'],
            'regular id' => [1, 'baa'],
            'max id' => [26, 'ccc'],
            'too big id' => [99, ''],
        ];
    }


}