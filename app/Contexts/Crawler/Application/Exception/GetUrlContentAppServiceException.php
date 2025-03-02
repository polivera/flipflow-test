<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Exception;

use Exception;

final class GetUrlContentAppServiceException extends Exception
{
    public static function ofInvalidArgument(Exception $exception): self
    {
        return new self("Invalid argument provided for getUrlContentAppService.", 0, $exception);
    }

    public static function onPageCrawl(Exception $exception): self
    {
        return new self("Crawl page failed on getUrlContentAppService.", 0, $exception);
    }

    public static function ofScrapProductsError(Exception $exception): self
    {
        return new self("Error when trying to scrap product page", 0, $exception);
    }
}
