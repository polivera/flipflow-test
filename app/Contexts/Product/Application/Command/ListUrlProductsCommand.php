<?php

declare(strict_types=1);

namespace App\Contexts\Product\Application\Command;

final readonly class ListUrlProductsCommand
{
    private function __construct(public string $url)
    {
    }

    public static function create(string $url): self
    {
        return new self($url);
    }
}
