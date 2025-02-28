<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\Url;
use App\Shared\Domain\ValueObject\NumberID;

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

    public static function create(
        NumberID $crawledPageID,
        ProductName $productName,
        Price $price,
        Url $imageUrl,
        Url $productUrl,
    ): self
    {
        return new self(NumberID::empty(), $crawledPageID, $productName, $price, $imageUrl, $productUrl);
    }

    public static function build(
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
