<?php

declare(strict_types=1);

namespace App\Contexts\Product\Interface\Dto;

use App\Contexts\Product\Domain\ValueObject\ProductList;

final readonly class ProductListResponse
{
    private function __construct(
        public array $data,
    ) {
    }

    public function toArray(): array
    {
        $arrayResult = [];
        foreach ($this->data as $item) {
            $arrayResult[] = $item->toArray();
        }
        return $arrayResult;
    }

    public static function fromValueObject(ProductList $productList): self
    {
        $response = [];
        foreach ($productList->iterator() as $result) {
            $response[] = ProductItemResponse::fromValueObject($result);
        }
        return new self($response);
    }

    public function toPrettyJson(): string
    {
        return json_encode($this->toArray(),  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
