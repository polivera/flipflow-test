<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;

use App\Contexts\Crawler\Domain\ValueObject\PageConfig;
use App\Shared\Domain\ValueObject\Url;

interface PageConfigRepositoryInterface
{
    public function getForUrl(Url $url): ?PageConfig;

    public function save(PageConfig $pageConfig): PageConfig;
}
