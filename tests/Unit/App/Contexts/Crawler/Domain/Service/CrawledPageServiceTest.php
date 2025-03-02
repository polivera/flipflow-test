<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Domain\Service;

use App\Contexts\Crawler\Domain\Contracts\ContentFetchInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawledPagesRepositoryInterface;
use App\Contexts\Crawler\Domain\Exception\CrawlPageServiceException;
use App\Contexts\Crawler\Domain\Service\CrawledPageService;
use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\CrawledPageStub;
use Tests\Stubs\Context\Crawler\ValueObject\PageContentStub;
use Tests\Stubs\Shared\ValueObject\UrlStub;

#[CoversClass(CrawledPageService::class)] final class CrawledPageServiceTest extends TestCase
{
    private MockInterface|ContentFetchInterface $contentFetchMock;
    private MockInterface|CrawledPagesRepositoryInterface $crawledPagesRepositoryMock;
    private CrawledPageService $service;

    public function setUp(): void
    {
        $this->contentFetchMock = Mockery::mock(ContentFetchInterface::class);
        $this->crawledPagesRepositoryMock = Mockery::mock(CrawledPagesRepositoryInterface::class);
        $this->service = new CrawledPageService($this->contentFetchMock, $this->crawledPagesRepositoryMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetContentThrowsException(): void
    {
        $mockUrl = UrlStub::random();
        $this->contentFetchMock
            ->shouldReceive('getContent')
            ->once()
            ->with($mockUrl)
            ->andThrow(ContentFetchException::class);
        $this->crawledPagesRepositoryMock
            ->shouldNotReceive('save');

        $this->expectException(CrawlPageServiceException::class);
        $this->expectExceptionMessage("Cannot retrieve content from url $mockUrl->value");

        $this->service->handle($mockUrl);
    }

    public function testGetContentReturnsNoContent(): void
    {
        $mockUrl = UrlStub::random();
        $this->contentFetchMock
            ->shouldReceive('getContent')
            ->once()
            ->with($mockUrl)
            ->andReturn(null);
        $this->crawledPagesRepositoryMock
            ->shouldNotReceive('save');

        $this->expectException(CrawlPageServiceException::class);
        $this->expectExceptionMessage("Page content not found (url: $mockUrl->value)");

        $this->service->handle($mockUrl);
    }

    public function testGetContentReturnsContent(): void
    {
        $mockUrl = UrlStub::random();
        $mockContent = PageContentStub::random();
        $crawledPage = CrawledPageStub::random();

        $this->contentFetchMock
            ->shouldReceive('getContent')
            ->once()
            ->with($mockUrl)
            ->andReturn($mockContent);
        $this->crawledPagesRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->withArgs(function (CrawledPage $crawledPage) use ($mockUrl, $mockContent) {
                $this->assertEquals($mockContent, $crawledPage->content);
                $this->assertEquals($mockUrl, $crawledPage->url);
                return true;
            })
            ->andReturn($crawledPage);

        $result = $this->service->handle($mockUrl);
        $this->assertEquals($crawledPage, $result);
    }
}
