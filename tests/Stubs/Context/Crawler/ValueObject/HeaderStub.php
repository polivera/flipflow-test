<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\Header;

final readonly class HeaderStub
{
    public static function random(): Header
    {
        return Header::create(fake()->name(), fake()->word());
    }
}
