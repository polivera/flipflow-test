<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Repository;

use App\Contexts\Crawler\Domain\Contracts\PageConfigRepositoryInterface;
use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Crawler\Domain\ValueObject\PageConfig;
use App\Contexts\Crawler\Infrastructure\Persistence\Mappers\PageConfigMapper;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\PageConfigModel;
use App\Shared\Domain\ValueObject\Url;
use Illuminate\Support\Facades\DB;

final readonly class PageConfigSQLiteRepository implements PageConfigRepositoryInterface
{
    public function getForUrl(Url $url): ?PageConfig
    {
        $record = DB::table(PageConfigModel::TABLE_NAME)
            ->select()
            ->where(PageConfigModel::SITE, '=', $url->value)
            ->orWhere(PageConfigModel::SITE, '=', Domain::fromUrl($url))
            ->orWhere(PageConfigModel::SITE, '=', PageConfigModel::DEFAULT_CONFIG_SITE)
            ->orderBy(PageConfigModel::PRIORITY, 'DESC')
            ->first();

        dd($record);
        // TODO: Logic missing here
    }

    public function save(PageConfig $pageConfig): PageConfig
    {
        $model = PageConfigMapper::toModel($pageConfig);
        $model->save();
        return PageConfigMapper::toValueObject($model);
    }
}
