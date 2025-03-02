<?php

declare(strict_types=1);

namespace Tests\Stubs\Shared\ValueObject;

use App\Shared\Domain\ValueObject\Url;

final readonly class UrlStub
{
    public static function random(): Url
    {
        return Url::create(fake()->url());
    }
}
