<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;

interface ScrapedProductsRepositoryInterface
{
    // TODO: Change that int maybe?
    public function saveBulk(ScrapedContentList $scrapedContentList): int;
}
