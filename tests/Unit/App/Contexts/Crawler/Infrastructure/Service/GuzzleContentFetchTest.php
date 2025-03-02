<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Infrastructure\Service;

use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
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

    public function testGetContentReturnsPageContentOnSuccess(): void
    {
        $url = Url::create('https://example.com');
        $responseBody = '<html lang="en"><body>Hello World</body></html>';

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

        $result = $this->contentFetcher->getContent($url);

        $this->assertInstanceOf(PageContent::class, $result);
        $this->assertEquals($responseBody, $result->body);
    }

    public function testGetContentReturnsNullOnUnsuccessfulResponse(): void
    {
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

        $result = $this->contentFetcher->getContent($url);

        $this->assertNull($result);
    }

    public function testHttpGetThrowsConnectionException(): void
    {
        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

        Http::shouldReceive('withOptions')
            ->once()
            ->with(['cookies' => $this->cookieJarMock])
            ->andReturnSelf();

        Http::shouldReceive('withHeaders')
            ->once()
            ->withArgs(function(array $headers) {
                $this->assertEquals(array_keys($headers), $this->shouldContainHeaders());
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
        $url = Url::create('https://example.com');

        $responseMock = Mockery::mock(Response::class);
        $responseMock->shouldReceive('successful')->once()->andReturn(false);

        Http::shouldReceive('withOptions')
            ->once()
            ->with(['cookies' => $this->cookieJarMock])
            ->andReturnSelf();

        Http::shouldReceive('withHeaders')
            ->once()
            ->withArgs(function(array $headers) {
                $this->assertEquals($this->shouldContainHeaders(), array_keys($headers));
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

    private function shouldContainHeaders(): array
    {
        return [
            'User-Agent',
            'Accept',
            'Accept-Encoding',
            'Accept-Language',
            'Cache-Control',
            'Connection',
            'Pragma',
            'Priority',
            'Sec-Ch-Ua',
            'Sec-Ch-Ua-Mobile',
            'Sec-Ch-Ua-Platform',
            'Sec-Fetch-Dest',
            'Sec-Fetch-Mode',
            'Sec-Fetch-Site',
            'Sec-Fetch-User',
            'Upgrade-Insecure-Requests',
        ];
    }
}
