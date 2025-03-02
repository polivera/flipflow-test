<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Application\Command;

final readonly class ScrapProductPageCommand
{
    private function __construct(
        public int $pageId
    ) {}

    public static function create(int $id): ScrapProductPageCommand
    {
        return new self($id);
    }
}
