<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Scraper\ValueObject;

use App\Contexts\Scraper\Domain\ValueObject\ScrapedProduct;
use Tests\Stubs\Shared\ValueObject\NumberIDStub;
use Tests\Stubs\Shared\ValueObject\PriceStub;
use Tests\Stubs\Shared\ValueObject\UrlStub;

final readonly class ScrapedProductStub
{
    public static function random(): ScrapedProduct
    {
        return ScrapedProduct::create(
            id: NumberIDStub::random(),
            crawledPageID: NumberIDStub::random(),
            productName: ProductNameStub::random(),
            price: PriceStub::random(),
            imageUrl: UrlStub::random(),
            productUrl: UrlStub::random(),
        );
    }
}
