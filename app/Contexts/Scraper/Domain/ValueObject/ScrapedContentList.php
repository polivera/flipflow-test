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
                throw new InvalidArgumentException('Array must contain only ScrapedContent instances');
            }
        }

        return new self($data);
    }
}
