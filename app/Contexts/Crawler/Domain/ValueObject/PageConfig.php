<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

use App\Shared\Domain\ValueObject\NumberID;
use App\Shared\Domain\ValueObject\Url;

final readonly class PageConfig
{
    private function __construct(
        public NumberID $id,
        public Url $url,
        public ConfigPriority $priority,
        public HeaderList $headers,
        public CookieList $cookies,
    ) {
    }

    public static function create(
        NumberID $id,
        Url $url,
        ConfigPriority $priority,
        HeaderList $headers,
        CookieList $cookies
    ): self {
        return new self($id, $url, $priority, $headers, $cookies);
    }

    public static function createWithoutID(
        Url $url,
        ConfigPriority $priority,
        HeaderList $headers,
        CookieList $cookies
    ): self {
        return new self(NumberID::empty(), $url, $priority, $headers, $cookies);
    }
}
