<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Contract;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;

interface GetUrlContentAppServiceInterface
{
    public function handle(GetUrlContentCommand $command): void;
}
