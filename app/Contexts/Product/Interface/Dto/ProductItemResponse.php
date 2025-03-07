<?php

declare(strict_types=1);

namespace App\Contexts\Product\Interface\Dto;

use App\Contexts\Product\Domain\ValueObject\Product;

final readonly class ProductItemResponse
{
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

    public function toPrettyJson(): string
    {
        return json_encode($this->toArray(),  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
