<?php

declare(strict_types=1);

namespace App\Contexts\Product\Infrastructure\Laravel;

use App\Contexts\Product\Application\Contract\ListUrlProductsAppServiceInterface;
use App\Contexts\Product\Application\Service\ListUrlProductsAppService;
use App\Contexts\Product\Domain\Contract\ListPageProductsServiceInterface;
use App\Contexts\Product\Domain\Contract\PageProductReaderInterface;
use App\Contexts\Product\Domain\Service\ListPageProductsService;
use App\Contexts\Product\Infrastructure\Persistence\Repository\PageProductReaderSQLiteRepository;
use App\Contexts\Product\Interface\Console\GetProductList;
use Carbon\Laravel\ServiceProvider;

final class ProductContextServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GetProductList::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->app->bind(ListUrlProductsAppServiceInterface::class, ListUrlProductsAppService::class);
        $this->app->bind(ListPageProductsServiceInterface::class, ListPageProductsService::class);
        $this->app->bind(PageProductReaderInterface::class, PageProductReaderSQLiteRepository::class);
    }
}
