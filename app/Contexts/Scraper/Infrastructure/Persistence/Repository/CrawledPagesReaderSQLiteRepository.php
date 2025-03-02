<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Persistence\Repository;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Infrastructure\Persistence\Mappers\CrawledPagesMapper;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Shared\Domain\ValueObject\NumberID;

final readonly class CrawledPagesReaderSQLiteRepository implements CrawledPagesReaderInterface
{
    public function getById(NumberID $crawledPageID): ?CrawledPage
    {
        $data = CrawledPagesModel::query()->where(CrawledPagesModel::ID, $crawledPageID->value)->first();
        if ($data !== null) {
            return CrawledPagesMapper::toValueObject($data);
        }
        return null;
    }
}
