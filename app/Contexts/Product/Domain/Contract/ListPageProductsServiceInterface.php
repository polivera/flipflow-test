<?php

declare(strict_types=1);

namespace App\Contexts\Product\Domain\Contract;

use App\Contexts\Product\Domain\ValueObject\ProductList;
use App\Shared\Domain\ValueObject\Url;

interface ListPageProductsServiceInterface
{
    public function handle(Url $page): ?ProductList;
}
