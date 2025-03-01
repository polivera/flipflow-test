<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Service;

use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapedProductsRepositoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Shared\Domain\ValueObject\Counter;
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

    public function handle(NumberID $crawledPageID): ScrapPageResults
    {
        $crawledPage = $this->crawledPagesReader->getById($crawledPageID);
        $scraper = $this->scraperProductFactory->createForDomain($crawledPage->domain);
        $listResult = $scraper->scrapProducts($crawledPage);
        $storedProducts = $this->scrapedProductsRepository->saveBulk($listResult);

        return ScrapPageResults::fromCurrentAction(
            $crawledPageID,
            $crawledPage->domain,
            Counter::from($storedProducts)
        );
    }
}
