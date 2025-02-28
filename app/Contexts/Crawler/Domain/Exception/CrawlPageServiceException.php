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
        return new self("Cannot retrieve content from url $url->value", 0, $exception);
    }
}
