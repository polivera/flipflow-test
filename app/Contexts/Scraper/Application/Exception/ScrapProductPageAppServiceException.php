<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Exception;

use App\Shared\Domain\ValueObject\Url;
use Exception;

final class ScrapProductPageAppServiceException extends Exception
{
    public static function ofInvalidCommandArgument(Exception $exception): self
    {
        return new self("Error con input arguments", 0, $exception);
    }

    public static function ofScrapError(int $pageID, Exception $exception): self
    {
        return new self("Error scrapping content for crawled page ID: " . $pageID, 0, $exception);
    }


}
