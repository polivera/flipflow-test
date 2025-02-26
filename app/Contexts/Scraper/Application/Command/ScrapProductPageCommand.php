<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Command;

final readonly class ScrapProductPageCommand
{
    public function __construct(
        public int $pageId
    ) {}
}
