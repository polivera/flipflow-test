<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Header
{
    private function __construct(
        public string $name,
        public string $value
    ){
        if ($name === '') {
            throw new InvalidArgumentException('Name cannot be empty');
        }
        if ($value === '') {
            throw new InvalidArgumentException('Value cannot be empty');
        }
    }

    public static function create(string $name, string $value): self
    {
        return new self($name, $value);
    }
}
