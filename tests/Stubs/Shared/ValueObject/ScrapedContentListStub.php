<?php

declare(strict_types=1);

namespace Tests\Stubs\Shared\ValueObject;

use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;
use Tests\Stubs\Context\Scraper\ValueObject\ScrapedProductStub;

final readonly class ScrapedContentListStub
{
    public static function random(): ScrapedContentList
    {
        return ScrapedContentList::fromArray(
            [
                ScrapedProductStub::random(),
                ScrapedProductStub::random(),
                ScrapedProductStub::random(),
            ]
        );
    }
}
