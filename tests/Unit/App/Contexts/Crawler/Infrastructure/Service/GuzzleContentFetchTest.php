<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Infrastructure\Service;

use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
use App\Contexts\Crawler\Infrastructure\Service\GuzzleContentFetch;
use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Domain\ValueObject\Url;
use GuzzleHttp\Cookie\CookieJarInterface;
use http\Client\Response;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GuzzleContentFetch::class)] final class GuzzleContentFetchTest extends TestCase
{
    private MockInterface $loggerMock;
    private MockInterface $cookieJarMock;
    private GuzzleContentFetch $contentFetcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loggerMock = Mockery::mock(LoggerInterface::class);
        $this->cookieJarMock = Mockery::mock(CookieJarInterface::class);

        $this->contentFetcher = new GuzzleContentFetch(
            $this->loggerMock,
            $this->cookieJarMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetContentReturnsPageContentOnSuccess(): void
    {
        // Arrange
        $url = Url::create('https://example.com');
        $responseBody = '<html><body>Hello World</body></html>';

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(true);
        $responseMock->shouldReceive('body')->once()->andReturn($responseBody);

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

        // Act
        $result = $this->contentFetcher->getContent($url);

        // Assert
        $this->assertInstanceOf(PageContent::class, $result);
        $this->assertEquals($responseBody, $result->body);
    }

    public function testGetContentReturnsNullOnUnsuccessfulResponse(): void
    {
        // Arrange
        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

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

        // Act
        $result = $this->contentFetcher->getContent($url);

        // Assert
        $this->assertNull($result);
    }

    public function testGetContentThrowsContentFetchException(): void
    {
        // Arrange
        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

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
            ->andThrow(ConnectionException::class);

        //TODO: Fix this
        $this->loggerMock->shouldReceive('error');

        $this->expectException(ContentFetchException::class);

        $result = $this->contentFetcher->getContent($url);

        $this->assertNull($result);
    }
}
