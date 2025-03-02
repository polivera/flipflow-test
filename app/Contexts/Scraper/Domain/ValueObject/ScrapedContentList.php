<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\ValueObject;

use ArrayIterator;
use Iterator;
use InvalidArgumentException;

final class ScrapedContentList
{
    private function __construct(private array $data)
    {
    }

    public function add(ScrapedProduct $scrapedContent): void
    {
        $this->data[] = $scrapedContent;
    }

    public function get(int $index): ?ScrapedProduct
    {
        return $this->data[$index] ?? null;
    }

    public function length(): int
    {
        return count($this->data);
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->data);
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function fromArray(array $data): self
    {
        foreach ($data as $item) {
            if (!($item instanceof ScrapedProduct)) {
                throw new InvalidArgumentException(
                    sprintf("Array must contain only %s instances", ScrapedProduct::class)
                );
            }
        }
        return new self($data);
    }
}
