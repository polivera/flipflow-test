<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Service;

use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapedProductsRepositoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Shared\Domain\ValueObject\NumberID;

final readonly class ScrapProductPageService implements ScrapProductPageServiceInterface
{
    public function __construct(
        private HostProductScraperFactoryInterface $scraperProductFactory,
        private CrawledPagesReaderInterface $crawledPagesReader,
        private ScrapedProductsRepositoryInterface $scrapedProductsRepository,
    )
    {
    }

    public function handle(NumberID $crawledPageID): void
    {
        $crawledPage = $this->crawledPagesReader->getById($crawledPageID);
        $scraper = $this->scraperProductFactory->createForDomain($crawledPage->domain);
        $listResult = $scraper->scrapProducts($crawledPage);
        $this->scrapedProductsRepository->saveBulk($listResult);
        dd($listResult);
        // TODO: Implement handle() method.
    }
}
