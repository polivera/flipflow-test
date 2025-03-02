<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Contract;

use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Scraper\Infrastructure\Exception\HostProductScraperFactoryException;

interface HostProductScraperFactoryInterface
{
    /**
     * @throws HostProductScraperFactoryException
     */
    public function createForDomain(Domain $domain): HostProductScraperInterface;
}
