<?php

declare(strict_types=1);

namespace App\Contexts\Product\Interface\Dto;

use App\Contexts\Product\Domain\ValueObject\Product;

final readonly class ProductListResponse
{
    /*
     * "name": "Hielo en cubitos Carrefour 2 kg.",
    "price": "0,85 €",
    "image_url": "https://static.carrefour.es/hd_350x_/img_pim_food/651364_00_1.jpg",
    "url": "https://www.carrefour.es/supermercado/hielo-en-cubitos-carrefour-2-kg/R-VC4AECOMM-651364/p",

     */
    private function __construct(
        public string $name,
        public string $price,
        public string $imageUrl,
        public string $productUrl,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'image_url' => $this->imageUrl,
            'url' => $this->productUrl,
        ];
    }

    public static function fromValueObject(Product $product): self
    {
        return new self(
            $product->productName->value,
            $product->price->toString(),
            $product->imageUrl->value,
            $product->productUrl->value
        );
    }
}
