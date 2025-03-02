<?php

declare(strict_types=1);

namespace Tests\Stubs\Shared\ValueObject;

use App\Shared\Domain\ValueObject\Counter;

final readonly class CounterStub
{
    public static function random(): Counter
    {
        return Counter::from(fake()->numberBetween());
    }
}
