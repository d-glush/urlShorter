<?php

namespace App\UrlShorterService\Tests;

use App\ShortUrlRepository\ShortUrlDTO;
use App\ShortUrlRepository\ShortUrlObj;
use PHPUnit\Framework\TestCase;

class ShortUrlObjTest extends TestCase
{
    protected function setUp(): void
    {
    }

    /**
     * @covers \App\ShortUrlRepository\ShortUrlObj::__construct
     * @dataProvider constructProvider
     */
    public function testConstruct(ShortUrlDTO $dto, ShortUrlObj $expectedShortUrlObj): void
    {
        $this->assertEquals($expectedShortUrlObj, new ShortUrlObj($dto));
    }

    public function constructProvider(): array
    {
        return [
            'with_id custom' => [
                new ShortUrlDTO([
                    'id' => 12,
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 1
                ]),
                (new ShortUrlObj(new ShortUrlDTO([
                    'id' => 0,
                    'full_url' => '',
                    'short_url' => '',
                    'is_custom' => 0
                ])))
                    ->setId(12)
                    ->setFullUrl('https://nomatt.er')
                    ->setShortUrl('aavas2')
                    ->setIsCustom(true)

            ],
            'without_id not_custom' => [
                new ShortUrlDTO([
                    'full_url' => 'https://nomatt.er',
                    'short_url' => 'aavas2',
                    'is_custom' => 0
                ]),
                (new ShortUrlObj(new ShortUrlDTO([
                    'full_url' => '',
                    'short_url' => '',
                    'is_custom' => 1
                ])))
                    ->setFullUrl('https://nomatt.er')
                    ->setShortUrl('aavas2')
                    ->setIsCustom(false)
            ],
        ];
    }


}