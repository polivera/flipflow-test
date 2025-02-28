<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Exception;

use App\Contexts\Crawler\Domain\ValueObject\Domain;
use Exception;

final class HostProductScraperFactoryException extends Exception
{
    public static function ofMappedDomainNotFound(Domain $domain): self
    {
        return new self("ProductScrapper class for domain $domain->value not found");
    }

    public static function ofConfigNotFound(): self
    {
        return new self("Scraper class map configuration not found");
    }

    public static function ofScraperClassBindError(Domain $domain, string $class, Exception $exception): self
    {
        return new self("Cannot bind class $class for domain $domain->value", 0, $exception);
    }
}
