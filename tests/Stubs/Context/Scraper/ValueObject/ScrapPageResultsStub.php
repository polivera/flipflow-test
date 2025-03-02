<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Scraper\ValueObject;

use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use Tests\Stubs\Context\Crawler\ValueObject\DomainStub;
use Tests\Stubs\Shared\ValueObject\CounterStub;
use Tests\Stubs\Shared\ValueObject\NumberIDStub;

final readonly class ScrapPageResultsStub
{
    public static function random(): ScrapPageResults
    {
        return ScrapPageResults::fromCurrentAction(
            NumberIDStub::random(),
            DomainStub::random(),
            CounterStub::random(),
        );
    }
}
