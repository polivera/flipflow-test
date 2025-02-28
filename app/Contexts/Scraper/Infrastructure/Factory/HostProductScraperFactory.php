<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Factory;

use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Config;

final readonly class HostProductScraperFactory implements HostProductScraperFactoryInterface
{
    public function __construct(private Container $container)
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function createForDomain(Domain $domain): HostProductScraperInterface
    {
        $productScraperMap = Config::get('scrapers.product', []);
        // TODO: Fix this exception
        if (!isset($productScraperMap[$domain->value])) {
            throw new \Exception("this is an error");
        }
        return $this->container->make($productScraperMap[$domain->value]);
    }
}
