<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

final readonly class Price
{
    // TODO: split amount and currency
    private function __construct(
        public int $value,
        public string $currency,
    )
    {
    }

    public static function create(int $value, string $currency): self
    {
        return new self($value, $currency);
    }

    // TODO: When split this should have different logic
    public static function fromString(string $value): self
    {
        // TODO: Improve this
        $numericValue = preg_replace('/[^0-9.]/', '', $value);
        $currencyCode = preg_replace('/[0-9., ]/', '', $value);
        return new self((int)$numericValue, $currencyCode);
    }
}
