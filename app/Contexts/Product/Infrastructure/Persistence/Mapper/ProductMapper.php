<?php

declare(strict_types=1);

namespace App\Contexts\Product\Infrastructure\Persistence\Mapper;

use App\Contexts\Product\Domain\ValueObject\Product;
use App\Contexts\Scraper\Domain\ValueObject\ProductName;
use App\Contexts\Scraper\Infrastructure\Persistence\Model\ScrapedProductsModel;
use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Price;
use App\Shared\Domain\ValueObject\Url;
use stdClass;

class ProductMapper
{
    public static function fromEntityArray(stdClass $data): Product
    {
        return Product::build(
            NumberID::create($data->{ScrapedProductsModel::ID}),
            NumberID::create($data->{ScrapedProductsModel::CRAWLED_PAGE_ID}),
            ProductName::create($data->{ScrapedProductsModel::NAME}),
            Price::create($data->{ScrapedProductsModel::PRICE}, $data->{ScrapedProductsModel::CURRENCY_CODE}),
            Url::create($data->{ScrapedProductsModel::IMAGE_URL}),
            Url::create($data->{ScrapedProductsModel::PRODUCT_URL}),
        );
    }
}
