<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Domain\Service;

use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapedProductsRepositoryInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Contexts\Scraper\Domain\Exception\ScrapProductPageServiceException;
use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Contexts\Scraper\Infrastructure\Exception\HostProductScraperFactoryException;
use App\Shared\Domain\ValueObject\Counter;
use App\Shared\Domain\ValueObject\NumberID;
use Exception;

final readonly class ScrapProductPageService implements ScrapProductPageServiceInterface
{
    public function __construct(
        private HostProductScraperFactoryInterface $scraperProductFactory,
        private CrawledPagesReaderInterface $crawledPagesReader,
        private ScrapedProductsRepositoryInterface $scrapedProductsRepository,
    ) {
    }

    /**
     * @throws ScrapProductPageServiceException
     */
    public function handle(NumberID $crawledPageID): ScrapPageResults
    {
        try {
            $crawledPage = $this->crawledPagesReader->getById($crawledPageID);
            if ($crawledPage === null) {
                throw ScrapProductPageServiceException::ofCrawledPageNotExist($crawledPageID);
            }
            $scraper = $this->scraperProductFactory->createForDomain($crawledPage->domain);
            $listResult = $scraper->scrapProducts($crawledPage);
            $storedProducts = $this->scrapedProductsRepository->saveBulk($listResult);

            return ScrapPageResults::fromCurrentAction(
                $crawledPageID,
                $crawledPage->domain,
                Counter::from($storedProducts)
            );
        } catch (HostProductScraperFactoryException $exception) {
            throw ScrapProductPageServiceException::ofFactoryError($crawledPageID, $exception);
        } catch (ScrapProductPageServiceException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw ScrapProductPageServiceException::ofUnexpectedError($crawledPageID, $exception);
        }
    }
}
