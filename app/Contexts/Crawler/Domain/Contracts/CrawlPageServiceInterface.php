<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Domain\ValueObject\Url;

interface CrawlPageServiceInterface
{
    public function handle(Url $url): ?CrawledPage;
}
