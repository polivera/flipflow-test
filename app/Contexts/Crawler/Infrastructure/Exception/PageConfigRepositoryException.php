<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Exception;

use App\Shared\Domain\ValueObject\Url;
use Exception;

final class PageConfigRepositoryException extends Exception
{
    public static function ofEmptyDefaultConfig(Url $url): self
    {
        return new self('Default page config should be defined. URL: ' . $url->value);
    }
}
