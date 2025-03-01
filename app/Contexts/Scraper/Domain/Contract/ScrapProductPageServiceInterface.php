<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Shared\Domain\ValueObject\NumberID;

interface ScrapProductPageServiceInterface
{
    public function handle(NumberID $crawledPageID): ScrapPageResults;
}
