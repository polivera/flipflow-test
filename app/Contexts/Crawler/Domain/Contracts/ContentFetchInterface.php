<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;

use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Infrastructure\Exception\ContentFetchException;
use App\Shared\Domain\ValueObject\Url;

interface ContentFetchInterface
{
    /**
     * @throws ContentFetchException
     */
    public function getContent(Url $url): ?PageContent;
}
