<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Factory;

use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperInterface;
use App\Contexts\Scraper\Infrastructure\Exception\HostProductScraperFactoryException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Config;

final readonly class HostProductScraperFactory implements HostProductScraperFactoryInterface
{
    public function __construct(private Container $container)
    {
    }

    /**
     * @throws HostProductScraperFactoryException
     */
    public function createForDomain(Domain $domain): HostProductScraperInterface
    {
        $productScraperMap = Config::get('scrapers.product', []);
        if (empty($productScraperMap)) {
            throw HostProductScraperFactoryException::ofConfigNotFound();
        }
        if (!isset($productScraperMap[$domain->value])) {
            throw HostProductScraperFactoryException::ofMappedDomainNotFound($domain);
        }

        try {
            return $this->container->make($productScraperMap[$domain->value]);
        } catch (BindingResolutionException $e) {
            throw HostProductScraperFactoryException::ofScraperClassBindError(
                $domain,
                $productScraperMap[$domain->value],
                $e
            );
        }
    }
}
