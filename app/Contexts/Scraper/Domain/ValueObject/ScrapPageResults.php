<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Shared\Domain\ValueObject\Counter;
use App\Shared\Domain\ValueObject\Date;
use App\Shared\Domain\ValueObject\NumberID;

final readonly class ScrapPageResults
{
    private function __construct(
        public NumberID $crawledPageID,
        public Domain $domain,
        public Counter $productsScraped,
        public Date $timestamp,
    )
    {}

    public static function fromCurrentAction(
        NumberID $crawledPageID,
        Domain $domain,
        Counter $productsScraped,
    ): self
    {
        return new self(
            $crawledPageID,
            $domain,
            $productsScraped,
            Date::now(),
        );
    }

    public function toArray(): array
    {
        return [

        ];
    }
}
