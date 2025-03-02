<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Service;

use App\Contexts\Crawler\Application\Command\GetUrlContentCommand;
use App\Contexts\Crawler\Application\Contract\GetUrlContentAppServiceInterface;
use App\Contexts\Crawler\Application\Exception\GetUrlContentAppServiceException;
use App\Contexts\Crawler\Domain\Contracts\CrawlPageServiceInterface;
use App\Contexts\Crawler\Domain\Exception\CrawlPageServiceException;
use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;
use App\Contexts\Scraper\Application\Contract\ScrapProductPageAppServiceInterface;
use App\Contexts\Scraper\Application\Exception\ScrapProductPageAppServiceException;
use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Shared\Domain\ValueObject\Url;
use InvalidArgumentException;

final readonly class GetUrlContentAppService implements GetUrlContentAppServiceInterface
{
    public function __construct(
        private CrawlPageServiceInterface $crawlPageService,
        // TODO: Change this to event?
        private ScrapProductPageAppServiceInterface $scrapProductPageAppService,
    )
    {
    }

    /**
     * @throws GetUrlContentAppServiceException
     */
    public function handle(GetUrlContentCommand $command): ScrapPageResults
    {
        try {
            $url = Url::create($command->url);
            $crawlPage = $this->crawlPageService->handle($url);
            return $this->scrapProductPageAppService->handle(
                ScrapProductPageCommand::create($crawlPage->id->value)
            );
        } catch (InvalidArgumentException $e) {
            throw GetUrlContentAppServiceException::ofInvalidArgument($e);
        } catch (CrawlPageServiceException $e) {
            throw GetUrlContentAppServiceException::onPageCrawl($e);
        } catch (ScrapProductPageAppServiceException $e) {
            throw GetUrlContentAppServiceException::ofScrapProductsError($e);
        }
    }
}
