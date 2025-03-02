<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Application\Service;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Exception\GetUrlContentAppServiceException;
use App\Contexts\Crawler\Application\Service\GetUrlContentAppService;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\Exception\CrawlPageServiceException;
use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;
use App\Contexts\Scraper\Application\Contract\ScrapProductPageAppServiceInterface;
use App\Contexts\Scraper\Application\Exception\ScrapProductPageAppServiceException;
use App\Shared\Domain\ValueObject\Url;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\CrawledPageStub;
use Tests\Stubs\Context\Scraper\ValueObject\ScrapPageResultsStub;

#[CoversClass(GetUrlContentAppService::class)] final class GetUrlContentAppServiceTest extends TestCase
{
    private MockInterface|CrawlPageServiceInterface $crawlPageServiceMock;
    private MockInterface|ScrapProductPageAppServiceInterface $scrapProductPageServiceMock;
    private GetUrlContentAppService $service;

    public function setUp(): void
    {
        $this->crawlPageServiceMock = Mockery::mock(CrawlPageServiceInterface::class);
        $this->scrapProductPageServiceMock = Mockery::mock(ScrapProductPageAppServiceInterface::class);
        $this->service = new GetUrlContentAppService($this->crawlPageServiceMock, $this->scrapProductPageServiceMock);
    }

    public function testInvalidUrlFromCommand(): void
    {
        $command = GetUrlContentCommand::create("");

        $this->expectException(GetUrlContentAppServiceException::class);
        $this->expectExceptionMessage("Invalid argument provided for getUrlContentAppService.");

        $this->service->handle($command);
    }

    public function testCrawlPageServiceHandlerThrowsException(): void
    {
        $command = GetUrlContentCommand::create("https://www.example.com");

        $this->crawlPageServiceMock
            ->shouldReceive('handle')
            ->withArgs(function (Url $url) use ($command) {
                $this->assertEquals($command->url, $url->value);
                return true;
            })
            ->andThrow(CrawlPageServiceException::class);

        $this->expectException(GetUrlContentAppServiceException::class);
        $this->expectExceptionMessage('Crawl page failed on getUrlContentAppService.');

        $this->service->handle($command);
    }

    public function testScrapProductPageServiceThrowsException(): void
    {
        $command = GetUrlContentCommand::create("https://www.example.com");
        $crawledPageMock = CrawledPageStub::random();

        $this->crawlPageServiceMock
            ->shouldReceive('handle')
            ->withArgs(function (Url $url) use ($command) {
                $this->assertEquals($command->url, $url->value);
                return true;
            })
            ->andReturn($crawledPageMock);

        $this->scrapProductPageServiceMock
            ->shouldReceive('handle')
            ->once()
            ->withArgs(function (ScrapProductPageCommand $command) use ($crawledPageMock) {
                $this->assertEquals($crawledPageMock->id->value, $command->pageId);
                return true;
            })
            ->andThrow(ScrapProductPageAppServiceException::class);

        $this->expectException(GetUrlContentAppServiceException::class);
        $this->expectExceptionMessage('Error when trying to scrap product page');

        $this->service->handle($command);
    }

    public function testHappyPath(): void
    {
        $command = GetUrlContentCommand::create("https://www.example.com");
        $crawledPageMock = CrawledPageStub::random();
        $scrapPageResultMock = ScrapPageResultsStub::random();

        $this->crawlPageServiceMock
            ->shouldReceive('handle')
            ->once()
            ->withArgs(function (Url $url) use ($command) {
                $this->assertEquals($command->url, $url->value);
                return true;
            })
            ->andReturn($crawledPageMock);

        $this->scrapProductPageServiceMock
            ->shouldReceive('handle')
            ->once()
            ->withArgs(function (ScrapProductPageCommand $command) use ($crawledPageMock) {
                $this->assertEquals($crawledPageMock->id->value, $command->pageId);
                return true;
            })
            ->andReturn($scrapPageResultMock);

        $result = $this->service->handle($command);
        $this->assertEquals($scrapPageResultMock, $result);
    }
}
