<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Exception;

use App\Shared\Domain\ValueObject\Url;
use Exception;
use PDOException;

final class CrawledPagesRepositoryException extends Exception
{
    public static function ofDatabaseError(Url $url, PDOException $e): self
    {
        return new self("Error saving crawled page Url: $url->value", 0, $e);
    }

    public static function ofUnexpectedError(Url $url, Exception $exception): self
    {
        return new self(
            "Unexpected error while saving crawled page: $url->value",
            0,
            $exception
        );
    }
}
