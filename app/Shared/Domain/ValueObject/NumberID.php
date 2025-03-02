<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

final readonly class NumberID
{
    /**
     * @throws InvalidArgumentException
     */
    private function __construct(public int $value) {
        if ($this->value < 0) {
            throw new InvalidArgumentException("Value must be positive");
        }
    }

    public static function empty(): self
    {
        return new self(0);
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function create(int $value): self
    {
        return new self($value);
    }

    public function isEmpty(): bool
    {
        return $this->value === 0;
    }

}
