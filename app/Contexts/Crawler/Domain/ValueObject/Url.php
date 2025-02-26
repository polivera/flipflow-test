<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use InvalidArgumentException;

final readonly class Url
{
    public function __construct(public string $value) {
    }
}
