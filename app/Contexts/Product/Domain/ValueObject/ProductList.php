<?php

declare(strict_types=1);

namespace App\Contexts\Product\Domain\ValueObject;

use ArrayIterator;
use Iterator;

final class ProductList
{
    private function __construct(private array $data)
    {
    }

    public function add(Product $scrapedContent): void
    {
        $this->data[] = $scrapedContent;
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->data);
    }

    public static function empty(): self
    {
        return new self([]);
    }
}
