<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Shared\Domain\ValueObject\NumberID;

interface CrawledPagesReaderInterface
{
    public function getById(NumberID $crawledPageID): ?CrawledPage;
}
