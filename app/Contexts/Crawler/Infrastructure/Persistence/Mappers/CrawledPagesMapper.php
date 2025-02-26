<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Mappers;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Domain\ValueObject\Url;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
use App\Shared\Domain\ValueObject\NumberID;

final readonly class CrawledPagesMapper
{
    public static function toModel(CrawledPage $crawledPage): CrawledPagesModel
    {
        $model = new CrawledPagesModel();
        if (!$crawledPage->id->isEmpty()) {
            $model->{CrawledPagesModel::ID} = $crawledPage->id->value;
        }
        $model->{CrawledPagesModel::URL} = $crawledPage->url->value;
        $model->{CrawledPagesModel::CONTENT} = $crawledPage->content->body;
        return $model;
    }

    public static function toValueObject(CrawledPagesModel $crawledPagesModel): CrawledPage
    {
        return new CrawledPage(
            new NumberID($crawledPagesModel->{CrawledPagesModel::ID}),
            new Url($crawledPagesModel->{CrawledPagesModel::URL}),
            new PageContent($crawledPagesModel->{CrawledPagesModel::CONTENT}),
        );
    }
}
