<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;

interface HostProductScraperInterface
{
    public function scrapProducts(CrawledPage $crawledPage): ScrapedContentList;
}
