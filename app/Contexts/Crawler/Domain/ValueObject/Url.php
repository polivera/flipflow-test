<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Url
{
    public function __construct(public string $value) {
    }

    public static function create(string $url): self
    {
        return new self($url);
    }
}
