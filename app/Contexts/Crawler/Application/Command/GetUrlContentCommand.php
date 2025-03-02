<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Command;

final readonly class GetUrlContentCommand
{
    private function __construct(
        public string $url,
    ){}

    public static function create(string $url):self
    {
        return new self($url);
    }
}
