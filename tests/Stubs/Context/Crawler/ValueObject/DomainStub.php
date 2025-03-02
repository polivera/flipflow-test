<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\Domain;

final readonly class DomainStub
{
    public static function random(): Domain
    {
        return Domain::create(fake()->domainName());
    }
}
