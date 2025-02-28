<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class Url
{
    public function __construct(public string $value) {
    }

    public static function create(string $url): self
    {
        return new self($url);
    }
}
