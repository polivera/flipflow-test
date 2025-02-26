<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Contract;

use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;

interface ScrapProductPageAppServiceInterface
{
    public function handle(ScrapProductPageCommand $command): void;
}
