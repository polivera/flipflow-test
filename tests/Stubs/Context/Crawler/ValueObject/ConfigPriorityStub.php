<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\ConfigPriority;

final readonly class ConfigPriorityStub
{
    public static function random(): ConfigPriority
    {
        return ConfigPriority::create(rand(1, 5));
    }
}
