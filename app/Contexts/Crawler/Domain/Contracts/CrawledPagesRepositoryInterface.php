<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;


use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;

interface CrawledPagesRepositoryInterface
{
    public function save(CrawledPage $pageContent): CrawledPage;
}
