<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Service;

use App\Contexts\Scraper\Application\Command\ScrapProductPageCommand;
use App\Contexts\Scraper\Application\Contract\ScrapProductPageAppServiceInterface;
use App\Contexts\Scraper\Application\Exception\ScrapProductPageAppServiceException;
use App\Contexts\Scraper\Domain\Contract\ScrapProductPageServiceInterface;
use App\Contexts\Scraper\Domain\Exception\ScrapProductPageServiceException;
use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Shared\Domain\ValueObject\NumberID;
use InvalidArgumentException;

final readonly class ScrapProductPageAppService implements ScrapProductPageAppServiceInterface
{
    public function __construct(
        private ScrapProductPageServiceInterface $scrapProductPageService
    )
    {
    }

    /**
     * @throws ScrapProductPageAppServiceException
     */
    public function handle(ScrapProductPageCommand $command): ScrapPageResults
    {
        try {
            $id = NumberID::create($command->pageId);
            return $this->scrapProductPageService->handle($id);
        } catch (InvalidArgumentException $exception) {
            throw ScrapProductPageAppServiceException::ofInvalidCommandArgument($exception);
        } catch (ScrapProductPageServiceException $exception) {
            throw ScrapProductPageAppServiceException::ofScrapError($command->pageId, $exception);
        }
    }
}
