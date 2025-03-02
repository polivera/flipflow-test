<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Price;
use App\Shared\Domain\ValueObject\Url;

final readonly class ScrapedProduct
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

    public static function createWithoutID(
        NumberID $crawledPageID,
        ProductName $productName,
        Price $price,
        Url $imageUrl,
        Url $productUrl,
    ): self
    {
        return new self(NumberID::empty(), $crawledPageID, $productName, $price, $imageUrl, $productUrl);
    }

    public static function create(
        NumberID $id,
        NumberID $crawledPageID,
        ProductName $productName,
        Price $price,
        Url $imageUrl,
        Url $productUrl,
    ): self
    {
        return new self($id, $crawledPageID, $productName, $price, $imageUrl, $productUrl);
    }
}
