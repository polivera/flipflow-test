<?php

declare(strict_types=1);

namespace App\Contexts\Product\Application\Service;

use App\Contexts\Product\Application\Command\ListUrlProductsCommand;
use App\Contexts\Product\Application\Contract\ListUrlProductsAppServiceInterface;
use App\Contexts\Product\Domain\Contract\ListPageProductsServiceInterface;
use App\Contexts\Product\Domain\ValueObject\ProductList;
use App\Shared\Domain\ValueObject\Url;

final readonly class ListUrlProductsAppService implements ListUrlProductsAppServiceInterface
{
    public function __construct(private ListPageProductsServiceInterface $listPageProductsService)
    {
    }

    public function handle(ListUrlProductsCommand $command): ProductList
    {
        $url = Url::create($command->url);
        return $this->listPageProductsService->handle($url);
    }
}
