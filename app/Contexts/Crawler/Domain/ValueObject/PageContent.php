<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

final readonly class PageContent
{
    public function __construct(public string $body) {}
}
