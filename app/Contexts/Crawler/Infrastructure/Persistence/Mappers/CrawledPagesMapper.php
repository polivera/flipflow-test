<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Mappers;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Url;

final readonly class CrawledPagesMapper
{
    public static function toModel(CrawledPage $crawledPage): CrawledPagesModel
    {
        $model = new CrawledPagesModel();
        if (!$crawledPage->id->isEmpty()) {
            $model->{CrawledPagesModel::ID} = $crawledPage->id->value;
        }
        $model->{CrawledPagesModel::DOMAIN} = $crawledPage->domain->value;
        $model->{CrawledPagesModel::URL} = $crawledPage->url->value;
        $model->{CrawledPagesModel::CONTENT} = $crawledPage->content->body;
        return $model;
    }

    public static function toValueObject(CrawledPagesModel $crawledPagesModel): CrawledPage
    {
        return CrawledPage::create(
            NumberID::create($crawledPagesModel->{CrawledPagesModel::ID}),
            Domain::create($crawledPagesModel->{CrawledPagesModel::DOMAIN}),
            Url::create($crawledPagesModel->{CrawledPagesModel::URL}),
            PageContent::create($crawledPagesModel->{CrawledPagesModel::CONTENT})
        );
    }
}
