<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;

interface ScrapedProductsRepositoryInterface
{
    public function saveBulk(ScrapedContentList $scrapedContentList): int;
}
