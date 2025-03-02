<?php

declare(strict_types=1);

namespace Tests\Stubs\Shared\ValueObject;

use App\Shared\Domain\ValueObject\Price;

final readonly class PriceStub
{
    public static function random(): Price
    {
        return Price::fromString(
            sprintf(
                "%f %s",
                fake()->randomFloat(nbMaxDecimals: 2),
                fake()->currencyCode()
            )
        );
    }
}
