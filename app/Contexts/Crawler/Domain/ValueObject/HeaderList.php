<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use ArrayIterator;
use Iterator;

final class HeaderList
{
    private function __construct(private array $data)
    {
    }

    public function add(Header $header): void
    {
        $this->data[$header->name] = $header;
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->data);
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public static function fromJson(string $json): self
    {
        return new self(json_decode($json, true));
    }
}
