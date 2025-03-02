<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Interface\Dto;

use App\Contexts\Scraper\Domain\ValueObject\ScrapPageResults;
use App\Shared\Domain\ValueObject\Url;

final readonly class ScrapPageResultResponse
{
    private function __construct(
        public string $url,
        public int $scrapedProductsCount,
        public string $time
    )
    {
    }

    public static function fromValueObject(string $url, ScrapPageResults $result): self
    {
        return new self(
            $url,
            $result->productsScraped->value,
            $result->timestamp->value->toString()
        );
    }

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'scrapedProductsCount' => $this->scrapedProductsCount,
            'timestamp' => $this->time,
        ];
    }

    public function toPrettyJson(): string
    {
        return json_encode($this->toArray(),  JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
