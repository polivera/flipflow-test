<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\PageConfig;
use Tests\Stubs\Shared\ValueObject\NumberIDStub;
use Tests\Stubs\Shared\ValueObject\UrlStub;

final readonly class PageConfigStub
{
    public static function random(int $headerCount = 3, int $cookieCount = 3): PageConfig
    {
        return PageConfig::create(
            NumberIDStub::random(),
            UrlStub::random(),
            ConfigPriorityStub::random(),
            HeaderListStub::random($headerCount),
            CookieListStub::random($cookieCount),
        );
    }
}
