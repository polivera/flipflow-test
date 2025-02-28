<?php

declare(strict_types=1);

namespace App\Contexts\Product\Application\Contract;

use App\Contexts\Product\Application\Command\ListUrlProductsCommand;
use App\Contexts\Product\Domain\ValueObject\ProductList;

interface ListUrlProductsAppServiceInterface
{
    public function handle(ListUrlProductsCommand $command): ProductList;
}
