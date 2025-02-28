<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

final readonly class ProductName
{
    private function __construct(public string $value)
    {
    }

    public static function create(string $value): self
    {
        return new self($value);
    }
}
