<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Scraper\Domain\Service;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Scraper\Domain\Contract\CrawledPagesReaderInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperFactoryInterface;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperInterface;
use App\Contexts\Scraper\Domain\Contract\ScrapedProductsRepositoryInterface;
use App\Contexts\Scraper\Domain\Exception\ScrapProductPageServiceException;
use App\Contexts\Scraper\Domain\Service\ScrapProductPageService;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;
use App\Contexts\Scraper\Infrastructure\Exception\HostProductScraperFactoryException;
use Exception;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\CrawledPageStub;
use Tests\Stubs\Shared\ValueObject\NumberIDStub;
use Tests\Stubs\Shared\ValueObject\ScrapedContentListStub;

#[CoversClass(ScrapProductPageService::class)] final class ScrapProductPageServiceTest extends TestCase
{
    private HostProductScraperFactoryInterface|MockInterface $scraperProductFactoryMock;
    private CrawledPagesReaderInterface|MockInterface $crawledPagesReaderMock;
    private ScrapedProductsRepositoryInterface|MockInterface $scrapedProductsRepositoryMock;
    private ScrapProductPageService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scraperProductFactoryMock = Mockery::mock(HostProductScraperFactoryInterface::class);
        $this->crawledPagesReaderMock = Mockery::mock(CrawledPagesReaderInterface::class);
        $this->scrapedProductsRepositoryMock = Mockery::mock(ScrapedProductsRepositoryInterface::class);

        $this->service = new ScrapProductPageService(
            $this->scraperProductFactoryMock,
            $this->crawledPagesReaderMock,
            $this->scrapedProductsRepositoryMock
        );
    }

    public function testCrawledPageNotFound(): void
    {
        $mockID = NumberIDStub::random();
        $this->crawledPagesReaderMock
            ->shouldReceive('getById')
            ->once()
            ->with($mockID)
            ->andReturnNull();

        $this->expectException(ScrapProductPageServiceException::class);
        $this->expectExceptionMessage('Cannot find crawled page to scrap with ID: ' . $mockID->value);

        $this->service->handle($mockID);
    }

    public function testCannotFindDomainScraper(): void
    {
        $mockID = NumberIDStub::random();
        $mockCrawledPage = CrawledPageStub::random();
        $this->crawledPagesReaderMock
            ->shouldReceive('getById')
            ->once()
            ->with($mockID)
            ->andReturn($mockCrawledPage);

        $this->scraperProductFactoryMock
            ->shouldReceive('createForDomain')
            ->once()
            ->with($mockCrawledPage->domain)
            ->andThrow(HostProductScraperFactoryException::class);

        $this->expectException(ScrapProductPageServiceException::class);
        $this->expectExceptionMessage('Error getting scraper for page ID: ' . $mockID->value);

        $this->service->handle($mockID);
    }

    public function testUnexpectedErrorThrown(): void
    {
        $mockID = NumberIDStub::random();
        $mockCrawledPage = CrawledPageStub::random();
        $this->crawledPagesReaderMock
            ->shouldReceive('getById')
            ->once()
            ->with($mockID)
            ->andReturn($mockCrawledPage);

        $this->scraperProductFactoryMock
            ->shouldReceive('createForDomain')
            ->once()
            ->with($mockCrawledPage->domain)
            ->andThrow(Exception::class);

        $this->expectException(ScrapProductPageServiceException::class);
        $this->expectExceptionMessage('Unexpected error when scraping product page ID: ' . $mockID->value);

        $this->service->handle($mockID);
    }

    public function testHappyPath(): void
    {
        $mockID = NumberIDStub::random();
        $mockCrawledPage = CrawledPageStub::random();
        $mockScraperClass = Mockery::mock(MockResultClass::class);
        $mockScrapList = ScrapedContentListStub::random();
        $mockSaveBulkResult = 5;
        $this->crawledPagesReaderMock
            ->shouldReceive('getById')
            ->once()
            ->with($mockID)
            ->andReturn($mockCrawledPage);

        $this->scraperProductFactoryMock
            ->shouldReceive('createForDomain')
            ->once()
            ->with($mockCrawledPage->domain)
            ->andReturn($mockScraperClass);

        $mockScraperClass
            ->shouldReceive('scrapProducts')
            ->once()
            ->with($mockCrawledPage)
            ->andReturn($mockScrapList);

        $this->scrapedProductsRepositoryMock
            ->shouldReceive('saveBulk')
            ->once()
            ->with($mockScrapList)
            ->andReturn($mockSaveBulkResult);

        $result = $this->service->handle($mockID);
        $this->assertEquals($mockID, $result->crawledPageID);
        $this->assertEquals($mockCrawledPage->domain->value, $result->domain->value);
        $this->assertEquals($mockSaveBulkResult, $result->productsScraped->value);

    }
}

class MockResultClass implements HostProductScraperInterface
{
    public function scrapProducts(CrawledPage $crawledPage): ScrapedContentList
    {
        return ScrapedContentListStub::random();
    }
}
