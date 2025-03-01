<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Application\Service;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Exception\GetUrlContentAppServiceException;
use App\Contexts\Crawler\Application\Service\GetUrlContentAppService;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\Exception\CrawlPageServiceException;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
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
    private MockInterface|ScrapProductPageServiceInterface $scrapProductPageServiceMock;
    private GetUrlContentAppService $service;

    public function setUp(): void
    {
        $this->crawlPageServiceMock = Mockery::mock(CrawlPageServiceInterface::class);
        $this->scrapProductPageServiceMock = Mockery::mock(ScrapProductPageServiceInterface::class);
        $this->service = new GetUrlContentAppService($this->crawlPageServiceMock, $this->scrapProductPageServiceMock);
    }

    public function testInvalidUrlFromCommand(): void
    {
        $command = new GetUrlContentCommand("");

        $this->expectException(GetUrlContentAppServiceException::class);
        $this->expectExceptionMessage("Invalid argument provided for getUrlContentAppService.");

        $this->service->handle($command);
    }

    public function testCrawlServiceHandlerThrowsException(): void
    {
        $command = new GetUrlContentCommand("https://www.example.com");

        $this->crawlPageServiceMock
            ->shouldReceive('handle')
            ->withArgs(function (Url $url) use ($command) {
                $this->assertEquals($command->url, $url->value);
                return true;
            })
            ->andThrow(CrawlPageServiceException::class);

        $this->expectException(GetUrlContentAppServiceException::class);
        $this->expectExceptionMessage("Crawl page failed on getUrlContentAppService.");

        $this->service->handle($command);
    }

    public function testHappyPath(): void
    {
        $command = new GetUrlContentCommand("https://www.example.com");
        $crawledPageMock = CrawledPageStub::random();
        $scrapPageResultMock = ScrapPageResultsStub::random();

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
            ->with($crawledPageMock->id)
            ->andReturn($scrapPageResultMock);

        $result = $this->service->handle($command);
        $this->assertEquals($scrapPageResultMock, $result);
    }
}
