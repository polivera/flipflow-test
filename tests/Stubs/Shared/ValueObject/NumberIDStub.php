<?php

declare(strict_types=1);

namespace Tests\Stubs\Shared\ValueObject;

use App\Shared\Domain\ValueObject\NumberID;

final readonly class NumberIDStub
{
    public static function random(): NumberID
    {
        return NumberID::create(rand(1, 1000));
    }
}
