<?php

declare(strict_types=1);

namespace App\Contexts\Product\Domain\ValueObject;

use App\Contexts\Scraper\Domain\ValueObject\ProductName;
use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Price;
use App\Shared\Domain\ValueObject\Url;

final readonly class Product
{
    private function __construct(
        public NumberID $id,
        public NumberID $crawledPageID,
        public ProductName $productName,
        public Price $price,
        public Url $imageUrl,
        public Url $productUrl,
    ) {
    }

    public static function build(
        NumberID $id,
        NumberID $crawledPageID,
        ProductName $productName,
        Price $price,
        Url $imageUrl,
        Url $productUrl,
    ): self {
        return new self($id, $crawledPageID, $productName, $price, $imageUrl, $productUrl);
    }
}
