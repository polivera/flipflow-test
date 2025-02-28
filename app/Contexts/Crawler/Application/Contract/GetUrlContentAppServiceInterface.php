<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Contract;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Exception\GetUrlContentAppServiceException;

interface GetUrlContentAppServiceInterface
{
    /**
     * @throws GetUrlContentAppServiceException
     */
    public function handle(GetUrlContentCommand $command): void;
}
