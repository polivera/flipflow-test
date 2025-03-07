<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Exception;

use App\Shared\Domain\ValueObject\Url;
use Exception;

final class ContentFetchException extends Exception
{
    public static function ofConnectionError(Url $url, Exception $previousException): self
    {
        return new self("Connection error when fetching content from $url->value", 0, $previousException);
    }

    public static function ofUnexpectedError(Url $url, Exception $previousException): self
    {
        return new self("Unexpected error when fetching content from $url->value", 0, $previousException);
    }

    public static function ofRetrievingPageConfigError(url $url, Exception $previousException): self
    {
        return new self("Error while retrieving page configuration for url $url->value", 0, $previousException);
    }
}
