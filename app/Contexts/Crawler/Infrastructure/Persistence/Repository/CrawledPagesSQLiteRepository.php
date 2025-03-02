<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Repository;

use App\Contexts\Crawler\Domain\Contracts\CrawledPagesRepositoryInterface;
use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Infrastructure\Exception\CrawledPagesRepositoryException;
use App\Contexts\Crawler\Infrastructure\Persistence\Mappers\CrawledPagesMapper;
use App\Shared\Domain\Contract\LoggerInterface;
use Exception;
use PDOException;

final readonly class CrawledPagesSQLiteRepository implements CrawledPagesRepositoryInterface
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @throws CrawledPagesRepositoryException
     */
    public function save(CrawledPage $pageContent): CrawledPage
    {
        try {
            $model = CrawledPagesMapper::toModel($pageContent);
            $model->save();
            return CrawledPagesMapper::toValueObject($model);
        } catch (PDOException $exception) {
            $this->logger->error(
                "Cannot save crawled page content",
                ['url' => $pageContent->url->value, 'exception' => $exception->getMessage()]
            );
            throw CrawledPagesRepositoryException::ofDatabaseError($pageContent->url, $exception);
        } catch (Exception $exception) {
            $this->logger->error(
                "Unexpected error while saving crawled page content",
                ['url' => $pageContent->url->value, 'exception' => $exception->getmessage()]
            );
            throw CrawledPagesRepositoryException::ofUnexpectedError($pageContent->url, $exception);
        }
    }
}
