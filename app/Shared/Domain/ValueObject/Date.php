<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use Carbon\CarbonImmutable;

final readonly class Date
{
    private function __construct(public CarbonImmutable $value) {

    }

    public static function now(): Date
    {
        return new self(CarbonImmutable::now());
    }
}
