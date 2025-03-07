<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Repository;

use App\Contexts\Crawler\Domain\Contracts\PageConfigRepositoryInterface;
use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Crawler\Domain\ValueObject\PageConfig;
use App\Contexts\Crawler\Infrastructure\Exception\PageConfigRepositoryException;
use App\Contexts\Crawler\Infrastructure\Persistence\Mappers\PageConfigMapper;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\PageConfigModel;
use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Domain\ValueObject\Url;

final readonly class PageConfigSQLiteRepository implements PageConfigRepositoryInterface
{

    public function __construct(
        private LoggerInterface $logger
    ){}

    /**
     * @throws PageConfigRepositoryException
     */
    public function getForUrl(Url $url): PageConfig
    {
        $record = PageConfigModel::where(function ($query) use ($url) {
            $query->where(PageConfigModel::SITE, '=', $url->value)
                ->orWhere(PageConfigModel::SITE, '=', Domain::fromUrl($url)->value)
                ->orWhere(PageConfigModel::SITE, '=', PageConfigModel::DEFAULT_CONFIG_SITE);
        })
            ->orderBy(PageConfigModel::PRIORITY, 'DESC')
            ->first();

        if (!$record) {
            $this->logger->error("Default configuration was not defined", ['url' => $url]);
            throw PageConfigRepositoryException::ofEmptyDefaultConfig($url);
        }

        return PageConfigMapper::toValueObject($record);
    }

    public function save(PageConfig $pageConfig): PageConfig
    {
        $model = PageConfigMapper::toModel($pageConfig);
        $model->save();
        return PageConfigMapper::toValueObject($model);
    }
}
