<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Application\Command;

final readonly class GetUrlContentCommand
{
    public function __construct(
        public string $url,
    ){}
}
