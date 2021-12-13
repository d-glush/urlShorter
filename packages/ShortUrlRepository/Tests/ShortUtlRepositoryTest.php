<?php

namespace App\UrlShorterService\Tests;

use App\ConfigService\ConfigService;
use App\DBConnection\DBConnection;
use App\ShortUrlRepository\ShortUrlDTO;
use App\ShortUrlRepository\ShortUrlObj;
use App\ShortUrlRepository\ShortUrlRepository;
use App\UrlShorterService\ShortUrlGenerator;
use PHPUnit\Framework\TestCase;

class ShortUtlRepositoryTest extends TestCase
{
    protected DBConnection $DBConnection;

    protected function setUp(): void
    {
        $this->DBConnection = $this->createMock(DBConnection::class);
    }


    public function getShortByShortUrlProvider(): array
    {
        return [
            'founded by shortUrl' => [
                'asdasd',
                'short_url = \'asdasd\'',
                [['id' => 1, 'full_url' => 'https://myfullurl', 'short_url' => 'asdasd1', 'is_custom' => 0]],
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 1,
                    'full_url' => 'https://myfullurl',
                    'short_url' => 'asdasd1',
                    'is_custom' => 0
                ]))
            ],
            'founded by fullUrl custom' => [
                'asdasd2',
                'short_url = \'asdasd2\'',
                [['id' => 1, 'full_url' => 'https://myfullurl2', 'short_url' => 'asdasd2', 'is_custom' => 1]],
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 1,
                    'full_url' => 'https://myfullurl2',
                    'short_url' => 'asdasd2',
                    'is_custom' => 1
                ]))
            ],
            'not founded by fullUrl' => [
                'asdasd3',
                'short_url = \'asdasd3\'',
                [],
                false
            ],
        ];
    }

    /**
     * @covers \App\ShortUrlRepository\ShortUrlRepository::getShortByShortUrl
     * @dataProvider getShortByShortUrlProvider
     */
    public function testGetShortByShortUrl(
        string $shortUrl,
        string $expectedWhere,
        array $selectResult,
        $expectedResult
    ): void
    {
        $shortUrlRepository = new ShortUrlRepository($this->DBConnection);

        $this->DBConnection
            ->expects($this->once())
            ->method('select')
            ->with('shorts', $expectedWhere)
            ->will($this->returnValue($selectResult));

        $this->assertEquals($expectedResult, $shortUrlRepository->getShortByShortUrl($shortUrl));
    }


    public function getShortByFullUrlProvider(): array
    {
        return [
            'founded by fullUrl' => [
                'https://myfullurl',
                false,
                'full_url = \'https://myfullurl\' AND is_custom=0',
                [['id' => 1, 'full_url' => 'https://myfullurl', 'short_url' => 'asd', 'is_custom' => 0]],
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 1,
                    'full_url' => 'https://myfullurl',
                    'short_url' => 'asd',
                    'is_custom' => 0
                ]))
            ],
            'founded by fullUrl custom' => [
                'https://myfullurl2',
                true,
                'full_url = \'https://myfullurl2\' AND is_custom<>0',
                [['id' => 1, 'full_url' => 'https://myfullurl2', 'short_url' => 'asd', 'is_custom' => 1]],
                new ShortUrlObj(new ShortUrlDTO([
                    'id' => 1,
                    'full_url' => 'https://myfullurl2',
                    'short_url' => 'asd',
                    'is_custom' => 1
                ]))
            ],
            'not founded by fullUrl' => [
                'https://myfullurl3',
                false,
                'full_url = \'https://myfullurl3\' AND is_custom=0',
                [],
                false
            ],
        ];
    }

    /**
     * @covers \App\ShortUrlRepository\ShortUrlRepository::getShortByFullUrl
     * @dataProvider getShortByFullUrlProvider
     */
    public function testGetShortByFullUrl(
        string $fullUrl,
        bool $isCustom,
        string $expectedWhere,
        array $selectResult,
        $expectedResult
    ): void {
        $shortUrlRepository = new ShortUrlRepository($this->DBConnection);

        $this->DBConnection
            ->expects($this->once())
            ->method('select')
            ->with('shorts', $expectedWhere)
            ->will($this->returnValue($selectResult));

        $this->assertEquals($expectedResult, $shortUrlRepository->getShortByFullUrl($fullUrl, $isCustom));
    }


    public function insertShortProvider(): array
    {
        return [
            'normal insert' => [
                new ShortUrlObj(new ShortUrlDTO([
                    'full_url' => 'asd',
                    'short_url' => 'asdd',
                    'is_custom' => 1,
                ])),
                12,
                12
            ],
        ];
    }

    /**
     * @covers \App\ShortUrlRepository\ShortUrlRepository::insertShort
     * @dataProvider insertShortProvider
     */
    public function testInsertShort(ShortUrlObj $shortObj, int $insertResult, int $expectedResult): void
    {
        $shortUrlRepository = new ShortUrlRepository($this->DBConnection);

        $this->DBConnection
            ->expects($this->once())
            ->method('insert')
            ->with(
                'shorts',
                [
                    'full_url' => 'asd',
                    'short_url' => 'asdd',
                    'is_custom' => 1
                ]
            )
            ->will($this->returnValue($insertResult));

        $this->assertEquals($expectedResult, $shortUrlRepository->insertShort($shortObj));
    }


    public function updateShortProvider(): array
    {
        return [
            'normal update' => [
                1,
                new ShortUrlObj(new ShortUrlDTO([
                    'full_url' => 'asd',
                    'short_url' => 'asdd',
                    'is_custom' => 1,
                ])),
                true,
                true
            ],
            'update error' => [
                1,
                new ShortUrlObj(new ShortUrlDTO([
                    'full_url' => 'asd',
                    'short_url' => 'asdd',
                    'is_custom' => 1,
                ])),
                false,
                false
            ]
        ];
    }

    /**
     * @covers \App\ShortUrlRepository\ShortUrlRepository::__construct
     * @covers \App\ShortUrlRepository\ShortUrlRepository::updateShort
     * @dataProvider updateShortProvider
     */
    public function testUpdateShort(int $id, ShortUrlObj $shortObj, $updateRes, $expectedResult): void
    {
        $shortUrlRepository = new ShortUrlRepository($this->DBConnection);

        $this->DBConnection
            ->expects($this->once())
            ->method('update')
            ->with(
                'shorts',
                $id,
                [
                    'full_url' => 'asd',
                    'short_url' => 'asdd',
                    'is_custom' => 1
                ]
            )
            ->will($this->returnValue($updateRes));

        $this->assertEquals($expectedResult, $shortUrlRepository->updateShort($id, $shortObj));
    }
}