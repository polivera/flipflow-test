<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Service;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Contract\GetUrlContentAppServiceInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Shared\Domain\ValueObject\Url;

final readonly class GetUrlContentAppService implements GetUrlContentAppServiceInterface
{
    public function __construct(
        private CrawlPageServiceInterface $crawlPageService,
        // TODO: Change this to event?
        private ScrapProductPageServiceInterface $scrapProductPageService,
    )
    {
    }

    public function handle(GetUrlContentCommand $command): void
    {
        // TODO: Validation
        $url = new Url($command->url);
        $crawlPage = $this->crawlPageService->handle($url);

        // TODO: Change this
        $this->scrapProductPageService->handle($crawlPage->id);
        var_dump($crawlPage);
        var_dump($crawlPage->id);
    }
}
