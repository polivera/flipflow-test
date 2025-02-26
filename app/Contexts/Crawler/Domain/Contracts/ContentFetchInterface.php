<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\Contracts;

use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Crawler\Domain\ValueObject\Url;

interface ContentFetchInterface
{
    public function getContent(Url $url): ?PageContent;
}
