<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Persistence\Mapper;

use App\Contexts\Scraper\Domain\ValueObject\ProductName;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedProduct;
use App\Contexts\Scraper\Infrastructure\Persistence\Model\ScrapedProductsModel;
use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Price;
use App\Shared\Domain\ValueObject\Url;

final readonly class ScrapedProductMapper
{
    public static function toModel(ScrapedProduct $scrapedProduct): ScrapedProductsModel
    {
        $model = new ScrapedProductsModel();
        if (!$scrapedProduct->id->isEmpty()) {
            $model->{ScrapedProductsModel::ID} = $scrapedProduct->id->value;
        }
        $model->{ScrapedProductsModel::CRAWLED_PAGE_ID} = $scrapedProduct->crawledPageID->value;
        $model->{ScrapedProductsModel::NAME} = $scrapedProduct->productName->value;
        $model->{ScrapedProductsModel::PRICE} = $scrapedProduct->price->value;
        $model->{ScrapedProductsModel::CURRENCY_CODE} = $scrapedProduct->price->currency;
        $model->{ScrapedProductsModel::PRODUCT_URL} = $scrapedProduct->productUrl->value;
        $model->{ScrapedProductsModel::IMAGE_URL} = $scrapedProduct->imageUrl->value;

        return $model;
    }

    public static function toValueObject(ScrapedProductsModel $scrapedProductsModel): ScrapedProduct
    {
        return ScrapedProduct::create(
            NumberID::create($scrapedProductsModel->{ScrapedProductsModel::ID}),
            NumberID::create($scrapedProductsModel->{ScrapedProductsModel::CRAWLED_PAGE_ID}),
            ProductName::create($scrapedProductsModel->{ScrapedProductsModel::NAME}),
            Price::create(
                $scrapedProductsModel->{ScrapedProductsModel::PRICE},
                $scrapedProductsModel->{ScrapedProductsModel::CURRENCY_CODE}
            ),
            Url::create($scrapedProductsModel->{ScrapedProductsModel::IMAGE_URL}),
            Url::create($scrapedProductsModel->{ScrapedProductsModel::PRODUCT_URL}),
        );
    }
}
