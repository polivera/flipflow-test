<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Url;

final readonly class CrawledPage
{
    public function __construct(
        public NumberID $id,
        public Domain $domain,
        public Url $url,
        public PageContent $content,
    ) {}

    public static function createWithoutID(Domain $domain, Url $url, PageContent $content): self
    {
        return new self(NumberID::empty(), $domain, $url, $content);
    }

    public static function create(NumberID $id, Domain $domain, Url $url, PageContent $content): self
    {
        return new self($id, $domain, $url, $content);
    }
}
