<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Domain\ValueObject;

final readonly class Domain
{
    public function __construct(public string $value) {
        // TODO: Domain validation
    }

    public static function fromUrl(Url $url): self
    {
        $domainPart = preg_replace('#^https?://#', '', $url->value);
        $domainEnd = strpos($domainPart, "/");
        $domainPart = substr($domainPart, 0, ($domainEnd !== false) ? $domainEnd : strlen($domainPart));
        return new self($domainPart);
    }
}
