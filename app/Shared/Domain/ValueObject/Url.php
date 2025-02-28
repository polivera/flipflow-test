<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Url
{
    /**
     * @throws InvalidArgumentException
     */
    private function __construct(public string $value) {
        if ($value == "") {
            throw new InvalidArgumentException("Url value cannot be empty");
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function create(string $url): self
    {
        return new self($url);
    }
}
