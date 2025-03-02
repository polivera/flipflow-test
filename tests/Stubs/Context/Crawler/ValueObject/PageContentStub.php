<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Crawler\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\PageContent;

final readonly class PageContentStub
{
    public static function random(): PageContent
    {
        return PageContent::create(fake()->randomHtml());
    }
}
