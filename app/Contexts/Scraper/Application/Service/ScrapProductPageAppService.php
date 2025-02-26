<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Service;

use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;
use App\Contexts\Scraper\Application\Contract\ScrapProductPageAppServiceInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Shared\Domain\ValueObject\NumberID;

final readonly class ScrapProductPageAppService implements ScrapProductPageAppServiceInterface
{
    public function __construct(
        private ScrapProductPageServiceInterface $scrapProductPageService
    )
    {
    }

    public function handle(ScrapProductPageCommand $command): void
    {
        // TODO: Exceptions?
        $id = new NumberID($command->pageId);
        $this->scrapProductPageService->handle($id);

    }
}
