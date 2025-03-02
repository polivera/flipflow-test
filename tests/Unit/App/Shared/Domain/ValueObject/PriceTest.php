<?php

declare(strict_types=1);

namespace Tests\Unit\App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Price;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{

    #[DataProvider('dataProvider')] public function testFromString(string $strPrice, int $amount, string $currency): void
    {
        $price = Price::fromString($strPrice);
        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals($amount, $price->value);
        $this->assertEquals($currency, $price->currency);
    }

    public static function dataProvider(): array
    {
        return [
            [
                'strPrice' => '123,45 $',
                'amount' => 12345,
                'currency' => '$',
            ],
            [
                'strPrice' => '123.45 $',
                'amount' => 12345,
                'currency' => '$',
            ],
            [
                'strPrice' => '$123,45',
                'amount' => 12345,
                'currency' => '$',
            ],
            [
                'strPrice' => '$123.45',
                'amount' => 12345,
                'currency' => '$',
            ],
            [
                'strPrice' => '$ 123,45',
                'amount' => 12345,
                'currency' => '$',
            ],
            [
                'strPrice' => '$ 123.45',
                'amount' => 12345,
                'currency' => '$',
            ]
        ];
    }
}
