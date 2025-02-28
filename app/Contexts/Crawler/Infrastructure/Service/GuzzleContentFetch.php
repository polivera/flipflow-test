<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Service;

use App\Contexts\Crawler\Domain\Contracts\ContentFetchInterface;
use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Domain\ValueObject\Url;
use GuzzleHttp\Cookie\CookieJarInterface;
use Illuminate\Support\Facades\Http;

final readonly class GuzzleContentFetch implements ContentFetchInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private CookieJarInterface $cookieJar
    )
    {
    }

    public function getContent(Url $url): ?PageContent
    {
        try {
            $requestOptions = ['cookies' => $this->cookieJar];

            $response = Http::withOptions($requestOptions)
                ->withHeaders($this->buildRequestHeaders())
                ->get($url->value);
            if ($response->successful()) {
                return new PageContent($response->body());
            }
            // TODO: Exception here, I should add common exception for this interface.
        } catch (\Throwable $e) {
           $this->logger->error("Error fetching content", ['exception' => $e, 'url' => $url->value]);
        }
        return null;
    }

    private function buildRequestHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, br, zstd',
            'Accept-Language' => 'en-US,en;q=0.9',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Pragma' => 'no-cache',
            'Sec-Fetch-Dest' => 'document',
            'Sec-Fetch-Mode' => 'navigate',
            'Sec-Fetch-Site' => 'none',
            'Sec-Fetch-User' => '?1',
            'Upgrade-Insecure-Requests' => '1',
        ];
    }
}
