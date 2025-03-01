<?php

declare(strict_types=1);

namespace App\Contexts\Product\Domain\Service;

use App\Contexts\Product\Domain\Contract\ListPageProductsServiceInterface;
use App\Contexts\Product\Domain\Contract\PageProductReaderInterface;
use App\Contexts\Product\Domain\ValueObject\ProductList;
use App\Shared\Domain\ValueObject\Url;

final readonly class ListPageProductsService implements ListPageProductsServiceInterface
{
    public function __construct(private PageProductReaderInterface $pageProductReader)
    {
    }

    public function handle(Url $page): ?ProductList
    {
        return $this->pageProductReader->findByPage($page);
    }
}
