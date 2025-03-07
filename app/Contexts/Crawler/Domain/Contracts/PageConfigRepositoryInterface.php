<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;

use App\Contexts\Crawler\Domain\ValueObject\PageConfig;
use App\Contexts\Crawler\Infrastructure\Exception\PageConfigRepositoryException;
use App\Shared\Domain\ValueObject\Url;

interface PageConfigRepositoryInterface
{
    /**
     * @throws PageConfigRepositoryException
     */
    public function getForUrl(Url $url): PageConfig;

    public function save(PageConfig $pageConfig): PageConfig;
}
