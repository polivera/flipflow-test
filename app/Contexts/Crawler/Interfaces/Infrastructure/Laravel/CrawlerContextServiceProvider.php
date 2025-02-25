<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Interfaces\Infrastructure\Laravel;

use App\Contexts\Crawler\Interfaces\Console\SaveProductList;
use Carbon\Laravel\ServiceProvider;

final class CrawlerContextServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SaveProductList::class,
            ]);
        }
    }
}
