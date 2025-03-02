<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Exception;


use App\Shared\Domain\ValueObject\NumberID;
use Exception;

final class ScrapProductPageServiceException extends Exception
{
    public static function ofCrawledPageNotExist(NumberID $crawledPageID): self
    {
        return new self('Cannot find crawled page to scrap with ID: ' . $crawledPageID->value);
    }

    public static function ofFactoryError(NumberID $crawledPageID, Exception $exception): self
    {
        return new self(
            "Error getting scraper for page ID: $crawledPageID->value.",
            0,
            $exception
        );
    }

    public static function ofUnexpectedError(NumberID $crawledPageID, Exception $exception): self
    {
        return new self(
            "Unexpected error when scraping product page ID: $crawledPageID->value.",
            0,
            $exception
        );
    }
}
