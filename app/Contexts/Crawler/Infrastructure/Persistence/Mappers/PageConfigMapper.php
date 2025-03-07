<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Mappers;

use App\Contexts\Crawler\Domain\ValueObject\ConfigPriority;
use App\Contexts\Crawler\Domain\ValueObject\CookieList;
use App\Contexts\Crawler\Domain\ValueObject\HeaderList;
use App\Contexts\Crawler\Domain\ValueObject\PageConfig;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\PageConfigModel;
use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Url;

final readonly class PageConfigMapper
{
    public static function toModel(PageConfig $pageConfig): PageConfigModel
    {
        $model = new PageConfigModel();
        if (!$pageConfig->id->isEmpty()) {
            $model->{PageConfigModel::ID} = $pageConfig->id->value;
        }
        $model->{PageConfigModel::SITE} = $pageConfig->url->value;
        $model->{PageConfigModel::PRIORITY} = $pageConfig->priority->value;
        $model->{PageConfigModel::HEADERS} = $pageConfig->headers->toJson();
        $model->{PageConfigModel::COOKIES} = $pageConfig->cookies->toJson();
        return $model;
    }

    public static function toValueObject(PageConfigModel $model): PageConfig
    {
        return PageConfig::create(
            NumberID::create($model->{PageConfigModel::ID}),
            Url::create($model->{PageConfigModel::SITE}),
            ConfigPriority::create($model->{PageConfigModel::PRIORITY}),
            HeaderList::fromJson($model->{PageConfigModel::HEADERS} ?? PageconfigModel::EMPTY_HEADERS_JSON),
            CookieList::fromJson($model->{PageConfigModel::COOKIES} ?? PageConfigModel::EMPTY_COOKIES_JSON),
        );
    }
}
