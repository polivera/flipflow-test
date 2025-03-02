<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Scraper\Infrastructure\Persistence\Mapper;

use App\Contexts\Scraper\Domain\ValueObject\ScrapedProduct;
use App\Contexts\Scraper\Infrastructure\Persistence\Mapper\ScrapedProductMapper;
use App\Contexts\Scraper\Infrastructure\Persistence\Model\ScrapedProductsModel;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Scraper\ValueObject\ScrapedProductStub;

#[CoversClass(ScrapedProductMapper::class)] final class ScrapedProductMapperTest extends TestCase
{
    public function testToModel(): void
    {
        $vo = ScrapedProductStub::random();
        $model = ScrapedProductMapper::toModel($vo);

        $this->assertInstanceOf(ScrapedProductsModel::class, $model);
        $this->assertEquals($vo->id->value, $model->{ScrapedProductsModel::ID});
        $this->assertEquals($vo->crawledPageID->value, $model->{ScrapedProductsModel::CRAWLED_PAGE_ID});
        $this->assertEquals($vo->productName->value, $model->{ScrapedProductsModel::NAME});
        $this->assertEquals($vo->price->value, $model->{ScrapedProductsModel::PRICE});
        $this->assertEquals($vo->price->currency, $model->{ScrapedProductsModel::CURRENCY_CODE});
        $this->assertEquals($vo->productUrl->value, $model->{ScrapedProductsModel::PRODUCT_URL});
        $this->assertEquals($vo->imageUrl->value, $model->{ScrapedProductsModel::IMAGE_URL});
    }

    public function testToVo(): void
    {
        $vo = ScrapedProductStub::random();
        $model = ScrapedProductMapper::toModel($vo);
        $result = ScrapedProductMapper::toValueObject($model);

        $this->assertInstanceOf(ScrapedProduct::class, $result);
        $this->assertEquals($model->{ScrapedProductsModel::ID}, $result->id->value);
        $this->assertEquals($model->{ScrapedProductsModel::CRAWLED_PAGE_ID}, $result->crawledPageID->value);
        $this->assertEquals($model->{ScrapedProductsModel::NAME}, $result->productName->value);
        $this->assertEquals($model->{ScrapedProductsModel::PRICE}, $result->price->value);
        $this->assertEquals($model->{ScrapedProductsModel::CURRENCY_CODE}, $result->price->currency);
        $this->assertEquals($model->{ScrapedProductsModel::PRODUCT_URL}, $result->productUrl->value);
        $this->assertEquals($model->{ScrapedProductsModel::IMAGE_URL}, $result->imageUrl->value);
    }
}
