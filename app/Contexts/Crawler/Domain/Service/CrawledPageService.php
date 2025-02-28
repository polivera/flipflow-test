<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Service;

use App\Contexts\Crawler\Domain\Contracts\ContentFetchInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawledPagesRepositoryInterface;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Shared\Domain\ValueObject\Url;
use App\Shared\Infrastructure\Service\LaravelLoggerService;

final readonly class CrawledPageService implements CrawlPageServiceInterface
{
    public function __construct(
        private ContentFetchInterface $contentFetch,
        private CrawledPagesRepositoryInterface $crawlPagesRepository,
        private LaravelLoggerService $logger,
    )
    {
    }

    public function handle(Url $url): ?CrawledPage
    {
        try {
            // TODO: review exceptions here
            $content = $this->contentFetch->getContent($url);
            return $this->crawlPagesRepository->save(CrawledPage::create(Domain::fromUrl($url) ,$url, $content));
        } catch (\Throwable $e) {
            $this->logger->error("Crawl page service error", ['exception' => $e, 'url' => $url]);
        }
        return null;
    }
}
