<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class Price
{
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

    public static function fromString(string $value): self
    {
        // TODO: Improve this
        $numericValue = preg_replace('/[^0-9]/', '', $value);
        $currencyCode = preg_replace('/[0-9., ]/', '', $value);
        return new self((int)$numericValue, $currencyCode);
    }

    public function toString(): string
    {
        $formatedNumber = number_format($this->value / 100, 2);
        return sprintf("%s %s", $formatedNumber, $this->currency);
    }
}
