<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Crawler\Domain\ValueObject\Domain;

interface HostProductScraperFactoryInterface
{
    public function createForDomain(Domain $domain): HostProductScraperInterface;
}
