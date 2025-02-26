<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Laravel;

use App\Contexts\Crawler\Application\Contract\GetUrlContentAppServiceInterface;
use App\Contexts\Crawler\Application\Service\GetUrlContentAppService;
use App\Contexts\Crawler\Domain\Contracts\ContentFetchInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawledPagesRepositoryInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\Service\CrawledPageService;
use App\Contexts\Crawler\Infrastructure\Persistence\Repository\CrawledPagesSQLiteRepository;
use App\Contexts\Crawler\Infrastructure\Service\GuzzleContentFetch;
use App\Contexts\Crawler\Interfaces\Console\SaveProductList;
use Carbon\Laravel\ServiceProvider;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\CookieJar;

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

    public function register(): void
    {
        $this->app->bind(CookieJarInterface::class, CookieJar::class);

        $this->app->bind(ContentFetchInterface::class, GuzzleContentFetch::class);
        $this->app->bind(CrawledPagesRepositoryInterface::class, CrawledPagesSQLiteRepository::class);
        $this->app->bind(CrawlPageServiceInterface::class, CrawledPageService::class);
        $this->app->bind(GetUrlContentAppServiceInterface::class, GetUrlContentAppService::class);
    }
}
