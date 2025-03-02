<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Exception;

use App\Shared\Domain\ValueObject\Url;
use Exception;

final class CrawlPageServiceException extends Exception
{
    public static function usingUrl(Url $url): self
    {
        return new self("Page content not found (url: $url->value)");
    }

    public static function ofFetchContentError(Url $url, Exception $exception): self
    {
        return new self("Cannot retrieve content from url $url->value", $exception->code, $exception);
    }

    public static function ofRepositoryError(Url $url, Exception $exception): self
    {
        return new self("Error while storing crawled page content for url $url->value", $exception->code, $exception);
    }
}
