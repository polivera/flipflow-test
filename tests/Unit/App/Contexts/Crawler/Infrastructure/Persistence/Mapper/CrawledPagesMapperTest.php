<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Infrastructure\Persistence\Mapper;

use App\Contexts\Crawler\Infrastructure\Persistence\Mappers\CrawledPagesMapper;
use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\CrawledPageStub;

#[CoversClass(CrawledPagesMapper::class)] class CrawledPagesMapperTest extends TestCase
{
    public function testToModel(): void
    {
        $vo = CrawledPageStub::random();
        $model = CrawledPagesMapper::toModel($vo);

        $this->assertEquals($vo->id->value, $model->{CrawledPagesModel::ID});
        $this->assertEquals($vo->domain->value, $model->{CrawledPagesModel::DOMAIN});
        $this->assertEquals($vo->url->value, $model->{CrawledPagesModel::URL});
        $this->assertEquals($vo->content->body, $model->{CrawledPagesModel::CONTENT});
    }

    public function testToVo(): void
    {
        // NOTE: I am lazy
        $vo = CrawledPageStub::random();
        $model = CrawledPagesMapper::toModel($vo);
        $resultVO = CrawledPagesMapper::toValueObject($model);

        $this->assertEquals($model->{CrawledPagesModel::ID}, $resultVO->id->value);
        $this->assertEquals($model->{CrawledPagesModel::DOMAIN}, $resultVO->domain->value);
        $this->assertEquals($model->{CrawledPagesModel::URL}, $resultVO->url->value);
        $this->assertEquals($model->{CrawledPagesModel::CONTENT}, $resultVO->content->body);
    }
}
