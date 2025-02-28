<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class NumberID
{
    public function __construct(public int $value) {
        if ($this->value < 0) {
            throw new \InvalidArgumentException("Value must be positive");
        }
    }

    public static function empty(): self
    {
        return new self(0);
    }

    public static function create(int $value): self
    {
        return new self($value);
    }

    public function isEmpty(): bool
    {
        return $this->value === 0;
    }

}
