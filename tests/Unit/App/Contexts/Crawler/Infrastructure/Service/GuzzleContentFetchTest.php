<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Infrastructure\Service;

use App\Contexts\Crawler\Domain\Contracts\PageConfigRepositoryInterface;
use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
use App\Contexts\Crawler\Infrastructure\Exception\PageConfigRepositoryException;
use App\Contexts\Crawler\Infrastructure\Service\GuzzleContentFetch;
use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Domain\ValueObject\Url;
use Exception;
use GuzzleHttp\Cookie\CookieJarInterface;
use http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\PageConfigStub;

#[CoversClass(GuzzleContentFetch::class)] final class GuzzleContentFetchTest extends TestCase
{
    private LoggerInterface|MockInterface $loggerMock;
    private CookieJarInterface|MockInterface $cookieJarMock;
    private PageConfigRepositoryInterface|MockInterface $pageConfigRepositoryMock;
    private GuzzleContentFetch $contentFetcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pageConfigRepositoryMock = Mockery::mock(PageConfigRepositoryInterface::class);
        $this->loggerMock = Mockery::mock(LoggerInterface::class);
        $this->cookieJarMock = Mockery::mock(CookieJarInterface::class);

        $this->contentFetcher = new GuzzleContentFetch(
            $this->pageConfigRepositoryMock,
            $this->loggerMock,
            $this->cookieJarMock
        );
    }

    public function testErrorRetrievingPageConfiguration(): void
    {
        $url = Url::create('https://example.com');
        $mockException = new PageConfigRepositoryException('mock page config exception');
        $this->pageConfigRepositoryMock
            ->shouldReceive('getForUrl')
            ->once()
            ->with($url)
            ->andThrow($mockException);

        $this->loggerMock
            ->shouldReceive('error')
            ->with(
                'Page config repository error retrieving content',
                ['url' => $url->value, 'exception' => $mockException]
            )
            ->once();

        $this->expectException(ContentFetchException::class);

        $this->contentFetcher->getContent($url);
    }

    public function testGetContentReturnsPageContentOnSuccess(): void
    {
        $url = Url::create('https://example.com');
        $mockCookieCount = 4;
        $this->pageConfigRepositoryMock
            ->shouldReceive('getForUrl')
            ->once()
            ->with($url)
            ->andReturn(PageConfigStub::random(cookieCount: $mockCookieCount));


        $responseBody = '<html lang="en"><body>Hello World</body></html>';

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(true);
        $responseMock->shouldReceive('body')->once()->andReturn($responseBody);

        $this->cookieJarMock
            ->shouldReceive('setCookie')
            ->times($mockCookieCount);

        Http::shouldReceive('withOptions')
            ->once()
            ->with(['cookies' => $this->cookieJarMock])
            ->andReturnSelf();

        Http::shouldReceive('withHeaders')
            ->once()
            ->andReturnSelf();

        Http::shouldReceive('get')
            ->once()
            ->with($url->value)
            ->andReturn($responseMock);

        $result = $this->contentFetcher->getContent($url);

        $this->assertInstanceOf(PageContent::class, $result);
        $this->assertEquals($responseBody, $result->body);
    }

    public function testGetContentReturnsNullOnUnsuccessfulResponse(): void
    {
        $mockCookieCount = 4;
        $this->pageConfigRepositoryMock
            ->shouldReceive('getForUrl')
            ->once()
            ->andReturn(PageConfigStub::random(cookieCount: $mockCookieCount));

        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

        $this->cookieJarMock
            ->shouldReceive('setCookie')
            ->times($mockCookieCount);

        Http::shouldReceive('withOptions')
            ->once()
            ->with(['cookies' => $this->cookieJarMock])
            ->andReturnSelf();

        Http::shouldReceive('withHeaders')
            ->once()
            ->andReturnSelf();

        Http::shouldReceive('get')
            ->once()
            ->with($url->value)
            ->andReturn($responseMock);

        $result = $this->contentFetcher->getContent($url);

        $this->assertNull($result);
    }

    public function testHttpGetThrowsConnectionException(): void
    {
        $mockCookieCount = 4;
        $pageConfig = PageConfigStub::random(cookieCount: $mockCookieCount);
        $this->pageConfigRepositoryMock
            ->shouldReceive('getForUrl')
            ->once()
            ->andReturn($pageConfig);

        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

        $this->cookieJarMock
            ->shouldReceive('setCookie')
            ->times($mockCookieCount);

        Http::shouldReceive('withOptions')
            ->once()
            ->with(['cookies' => $this->cookieJarMock])
            ->andReturnSelf();

        Http::shouldReceive('withHeaders')
            ->once()
            ->withArgs(function (array $headers) use ($pageConfig) {
                $this->assertEquals($pageConfig->headers->toArray(), $headers);
                return true;
            })
            ->andReturnSelf();

        $mockException = new ConnectionException('Connection Error');
        Http::shouldReceive('get')
            ->once()
            ->with($url->value)
            ->andThrow($mockException);

        $this->loggerMock->shouldReceive('error')
            ->with('Connection error retrieving content', ['url' => $url->value, 'exception' => $mockException])
            ->once();

        $this->expectException(ContentFetchException::class);
        $this->expectExceptionMessage('Connection error when fetching content from https://example.com');

        $result = $this->contentFetcher->getContent($url);

        $this->assertNull($result);
    }

    public function testHttpGetThrowsUnhandledException(): void
    {
        $mockCookieCount = 4;
        $pageConfig = PageConfigStub::random(cookieCount: $mockCookieCount);
        $this->pageConfigRepositoryMock
            ->shouldReceive('getForUrl')
            ->once()
            ->andReturn($pageConfig);

        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

        $this->cookieJarMock
            ->shouldReceive('setCookie')
            ->times($mockCookieCount);

        Http::shouldReceive('withOptions')
            ->once()
            ->with(['cookies' => $this->cookieJarMock])
            ->andReturnSelf();

        Http::shouldReceive('withHeaders')
            ->once()
            ->withArgs(function (array $headers) use ($pageConfig) {
                $this->assertEquals($pageConfig->headers->toArray(), $headers);
                return true;
            })
            ->andReturnSelf();

        $mockException = new Exception('Unhandled exception');
        Http::shouldReceive('get')
            ->once()
            ->with($url->value)
            ->andThrow($mockException);

        $this->loggerMock->shouldReceive('error')
            ->with('Unexpected error while fetching content', ['url' => $url->value, 'exception' => $mockException])
            ->once();

        $this->expectException(ContentFetchException::class);

        $result = $this->contentFetcher->getContent($url);

        $this->assertNull($result);
    }
}
