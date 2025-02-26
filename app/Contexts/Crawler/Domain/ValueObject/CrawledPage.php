<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use App\Shared\Domain\ValueObject\NumberID;

final readonly class CrawledPage
{
    public function __construct(
        public NumberID $id,
        public Url $url,
        public PageContent $content,
    ) {}

    public static function create(Url $url, PageContent $content): self
    {
        return new self(NumberID::empty(), $url, $content);
    }
}
