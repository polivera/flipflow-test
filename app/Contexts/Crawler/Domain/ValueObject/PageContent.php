<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

final readonly class PageContent
{
    private function __construct(public string $body)
    {
    }

    public static function create(string $body): self
    {
//        return new self(@mb_convert_encoding($body, 'HTML-ENTITIES', 'UTF-8'));
        return new self($body);
    }
}
