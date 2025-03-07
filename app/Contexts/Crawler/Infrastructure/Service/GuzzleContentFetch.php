<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Service;

use App\Contexts\Crawler\Domain\Contracts\ContentFetchInterface;
use App\Contexts\Crawler\Domain\Contracts\PageConfigRepositoryInterface;
use App\Contexts\Crawler\Domain\ValueObject\Cookie;
use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
use App\Contexts\Crawler\Infrastructure\Exception\PageConfigRepositoryException;
use App\Shared\Domain\Contract\LoggerInterface;
use App\Shared\Domain\ValueObject\Url;
use Exception;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\Cookie\SetCookie;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final readonly class GuzzleContentFetch implements ContentFetchInterface
{
    public function __construct(
        private PageConfigRepositoryInterface $pageConfigRepository,
        private LoggerInterface $logger,
        private CookieJarInterface $cookieJar
    ) {
    }

    /**
     * @throws ContentFetchException
     */
    public function getContent(Url $url): ?PageContent
    {
        try {
            $pageConfig = $this->pageConfigRepository->getForUrl($url);
            $domain = Domain::fromUrl($url);

            foreach ($pageConfig->cookies->toArray() as $cookie) {
                $this->cookieJar->setCookie($this->generateCookie($cookie, $domain));
            }

            $requestOptions = ['cookies' => $this->cookieJar];
            $response = Http::withOptions($requestOptions)
                ->withHeaders($pageConfig->headers->toArray())
                ->get($url->value);
            if ($response->successful()) {
                return PageContent::create($response->body());
            }

            return null;
        } catch (ConnectionException $exception) {
            $this->logger->error(
                'Connection error retrieving content',
                ['url' => $url->value, 'exception' => $exception]
            );
            throw ContentFetchException::ofConnectionError($url, $exception);
        } catch (PageConfigRepositoryException $exception) {
            $this->logger->error(
                'Page config repository error retrieving content',
                ['url' => $url->value, 'exception' => $exception]
            );
            throw ContentFetchException::ofRetrievingPageConfigError($url, $exception);
        } catch (Exception $exception) {
            $this->logger->error(
                'Unexpected error while fetching content',
                ['url' => $url->value, 'exception' => $exception]
            );
            throw ContentFetchException::ofUnexpectedError($url, $exception);
        }
    }

    private function generateCookie(Cookie $cookie, Domain $domain): SetCookie
    {
        return new SetCookie([
            'Name' => $cookie->name,
            'Value' => $cookie->value,
            'Domain' => $domain->value,
            'Path' => '/'
        ]);
    }
}
