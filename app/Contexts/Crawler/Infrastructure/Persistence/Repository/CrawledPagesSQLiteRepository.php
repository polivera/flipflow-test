<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Repository;

use App\Contexts\Crawler\Domain\Contracts\CrawledPagesRepositoryInterface;
use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Infrastructure\Persistence\Mappers\CrawledPagesMapper;

final readonly class CrawledPagesSQLiteRepository implements CrawledPagesRepositoryInterface
{
    public function save(CrawledPage $pageContent): CrawledPage
    {
        $model = CrawledPagesMapper::toModel($pageContent);
        $model->save();
        return CrawledPagesMapper::toValueObject($model);
    }
}
