<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Contract;

use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;
use App\Contexts\Scraper\Application\Exception\ScrapProductPageAppServiceException;
use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;

interface ScrapProductPageAppServiceInterface
{
    /**
     * @throws ScrapProductPageAppServiceException
     */
    public function handle(ScrapProductPageCommand $command): ScrapPageResults;
}
