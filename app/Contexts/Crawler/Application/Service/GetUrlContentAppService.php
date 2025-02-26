<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Service;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Contract\GetUrlContentAppServiceInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\ValueObject\Url;

final readonly class GetUrlContentAppService implements GetUrlContentAppServiceInterface
{
    public function __construct(
        private CrawlPageServiceInterface $crawlPageService
    )
    {
    }

    public function handle(GetUrlContentCommand $command): void
    {
        // TODO: Validation
        $url = new Url($command->url);
        $crawlPage = $this->crawlPageService->handle($url);
        var_dump($crawlPage);
        var_dump($crawlPage->id);
    }
}
