<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Shared\Domain\ValueObject\NumberID;

interface ScrapProductPageServiceInterface
{
    public function handle(NumberID $crawledPageID): void;
}
