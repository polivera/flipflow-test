<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Service;

use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Shared\Domain\ValueObject\NumberID;

final readonly class ScrapProductPageService implements ScrapProductPageServiceInterface
{
    public function __construct(
        private CrawledPagesReaderInterface $crawledPagesReader,
    )
    {

    }

    public function handle(NumberID $crawledPageID): void
    {
        $crawledPage = $this->crawledPagesReader->getById($crawledPageID);
        dump($crawledPage->content->body);
        // TODO: Implement handle() method.
    }
}
