<?php

declare(strict_types=1);

namespace App\Contexts\Product\Application\Command;

final readonly class ListUrlProductsCommand
{
    public function __construct(public string $url)
    {
    }
}
