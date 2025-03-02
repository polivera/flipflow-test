<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use Tests\Stubs\Shared\ValueObject\NumberIDStub;
use Tests\Stubs\Shared\ValueObject\UrlStub;

final readonly class CrawledPageStub
{
    public static function random(): CrawledPage
    {
        return CrawledPage::create(
            id: NumberIdStub::random(),
            domain: DomainStub::random(),
            url: UrlStub::random(),
            content: PageContentStub::random(),
        );
    }
}
