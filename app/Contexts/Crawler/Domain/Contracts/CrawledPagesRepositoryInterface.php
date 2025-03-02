<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;


use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Infrastructure\Exception\CrawledPagesRepositoryException;

interface CrawledPagesRepositoryInterface
{
    /**
     * @throws CrawledPagesRepositoryException
     */
    public function save(CrawledPage $pageContent): CrawledPage;
}
