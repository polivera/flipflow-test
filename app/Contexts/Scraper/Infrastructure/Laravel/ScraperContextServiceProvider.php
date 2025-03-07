<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Laravel;

use App\Contexts\Scraper\Application\Contract\ScrapProductPageAppServiceInterface;
use App\Contexts\Scraper\Application\Service\ScrapProductPageAppService;
use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapedProductsRepositoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Contexts\Scraper\Domain\Service\ScrapProductPageService;
use App\Contexts\Scraper\Infrastructure\Factory\HostProductScraperFactory;
use App\Contexts\Scraper\Infrastructure\Persistence\Repository\CrawledPagesReaderSQLiteRepository;
use App\Contexts\Scraper\Infrastructure\Persistence\Repository\ScrapedProductsSQLiteRepository;
use Carbon\Laravel\ServiceProvider;

final class ScraperContextServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->app->bind(ScrapProductPageAppServiceInterface::class, ScrapProductPageAppService::class);
        $this->app->bind(ScrapProductPageServiceInterface::class, ScrapProductPageService::class);
        $this->app->bind(CrawledPagesReaderInterface::class, CrawledPagesReaderSQLiteRepository::class);
        $this->app->bind(HostProductScraperFactoryInterface::class, HostProductScraperFactory::class);
        $this->app->bind(ScrapedProductsRepositoryInterface::class, ScrapedProductsSQLiteRepository::class);
    }
}
