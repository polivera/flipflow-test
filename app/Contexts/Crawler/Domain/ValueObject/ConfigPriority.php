<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use InvalidArgumentException;

final readonly class ConfigPriority
{
    private function __construct(public int $value)
    {
        if ($value<1 || $value > 5) {
            throw new InvalidArgumentException('Value must be between 1 and 5');
        }
    }

    public static function create(int $value): self
    {
        return new self($value);
    }
}
