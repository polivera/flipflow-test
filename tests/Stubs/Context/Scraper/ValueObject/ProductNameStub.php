<?php

declare(strict_types=1);

namespace Tests\Stubs\Context\Scraper\ValueObject;

use App\Contexts\Scraper\Domain\ValueObject\ProductName;

final readonly class ProductNameStub
{
    public static function random(): ProductName
    {
        return ProductName::create(fake()->company());
    }
}
