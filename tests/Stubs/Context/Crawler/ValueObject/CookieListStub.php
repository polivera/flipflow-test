<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\CookieList;

final readonly class CookieListStub
{
    public static function random(int $amount = 3): CookieList
    {
        $headers = [];
        for ($i = 0; $i < $amount; $i++) {
            $headers[] = CookieStub::random();
        }
        return CookieList::fromArray($headers);
    }
}
