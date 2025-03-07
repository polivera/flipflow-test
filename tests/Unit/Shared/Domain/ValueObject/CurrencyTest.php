<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\Currency;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{

    #[DataProvider('fromSymbolDataProvider')] public function testFromSymbol(string $symbol, Currency $currency): void
    {
        $result = Currency::fromSymbol($symbol);
        $this->assertEquals($currency, $result);
    }

    public function testFromSymbolThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Unknown currency 'F'");
        Currency::fromSymbol("F");
    }

    #[DataProvider('fromStringDataProvider')] public function testFromString(string $string, Currency $currency): void
    {
        $result = Currency::fromString($string);
        $this->assertEquals($currency, $result);
    }

    public function testFromStringThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Can't convert string 'WrongCurrency' to currency");

        Currency::fromString("WrongCurrency");
    }

    public static function fromSymbolDataProvider(): array
    {
        return [
            ['symbol' => '€', 'currency' => Currency::EUR],
            ['symbol' => '$', 'currency' => Currency::USD],
            ['symbol' => '£', 'currency' => Currency::GBP],
        ];
    }

    public static function fromStringDataProvider(): array
    {
        return [
            ['string' => '€', 'currency' => Currency::EUR],
            ['string' => '&acirc;&not;', 'currency' => Currency::EUR],
        ];
    }
}
