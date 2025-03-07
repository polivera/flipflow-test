<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

enum Currency: string
{
    case EUR = 'EUR';
    case USD = 'USD';
    case GBP = 'GBP';

    public function symbol(): string
    {
        return match ($this) {
            self::EUR => '€',
            self::USD => '$',
            self::GBP => '£',
        };
    }

    /**
     * @throw InvalidArgumentException
     */
    public static function fromSymbol(string $symbol): self
    {
        return match ($symbol) {
            '€' => self::EUR,
            '$' => self::USD,
            '£' => self::GBP,
            default => throw new InvalidArgumentException("Unknown currency '$symbol'"),
        };
    }

    /**
     * @throw InvalidArgumentException
     */
    public static function fromString(string $currency): self
    {
        try {
            return Currency::fromSymbol($currency);
        } catch (InvalidArgumentException) {
            return match (strtolower($currency)) {
                '&acirc;&not;', 'euro' => self::EUR,
                default => throw new InvalidArgumentException("Can't convert string '$currency' to currency"),
            };
        }
    }
}
