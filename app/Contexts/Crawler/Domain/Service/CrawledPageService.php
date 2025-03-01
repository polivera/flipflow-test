<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Service;

use App\Contexts\Crawler\Domain\Contracts\ContentFetchInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawledPagesRepositoryInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\Exception\CrawlPageServiceException;
use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
use App\Shared\Domain\ValueObject\Url;

final readonly class CrawledPageService implements CrawlPageServiceInterface
{
    public function __construct(
        private ContentFetchInterface $contentFetch,
        private CrawledPagesRepositoryInterface $crawlPagesRepository,
    )
    {
    }

    /**
     * @throws CrawlPageServiceException
     */
    public function handle(Url $url): ?CrawledPage
    {
        try {
            $content = $this->contentFetch->getContent($url);
            if (!$content) {
                throw CrawlPageServiceException::usingUrl($url);
            }
            return $this->crawlPagesRepository->save(
                CrawledPage::createWithoutID(Domain::fromUrl($url) ,$url, $content)
            );
        } catch (ContentFetchException $exception) {
            throw CrawlPageServiceException::ofFetchContentError($url, $exception);
        }
    }
}
