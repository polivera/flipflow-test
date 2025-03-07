<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\Cookie;

final readonly class CookieStub
{
    public static function random(): Cookie
    {
        return Cookie::create(fake()->name(), fake()->word());
    }
}
