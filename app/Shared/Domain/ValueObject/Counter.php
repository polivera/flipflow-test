<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Counter
{
    private function __construct(public int $value)
    {

    }

    public static function from(int $value): self
    {
        return new self($value);
    }
}
