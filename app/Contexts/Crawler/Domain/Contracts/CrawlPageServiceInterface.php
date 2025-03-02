<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;

use App\Contexts\Crawler\Domain\Exception\CrawlPageServiceException;
use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Shared\Domain\ValueObject\Url;

interface CrawlPageServiceInterface
{
    /**
     * @throws CrawlPageServiceException
     */
    public function handle(Url $url): ?CrawledPage;
}
