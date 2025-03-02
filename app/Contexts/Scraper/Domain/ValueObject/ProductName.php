<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

use InvalidArgumentException;

final readonly class ProductName
{
    /**
     * @throws InvalidArgumentException
     */
    private function __construct(public string $value)
    {
        if (empty($this->value)) {
            throw new InvalidArgumentException('Product name cannot be empty');
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function create(string $value): self
    {
        return new self($value);
    }
}
