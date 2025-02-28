<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Crawler\Domain\ValueObject;

use App\Contexts\Crawler\Domain\ValueObject\Domain;
use App\Shared\Domain\ValueObject\Url;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Domain::class)] class DomainTest extends TestCase
{
    #[DataProvider('fromUrlDataProvider')]
    public function test_get_domain_from_url($input, $expected): void
    {
        $mockUrl = new Url($input);
        $domain = Domain::fromUrl($mockUrl);

        $this->assertEquals($expected, $domain->value, "Expected domain $expected does not match $domain->value");
    }

    public static function fromUrlDataProvider(): array
    {
        return [
            ['https://www.example.com/path', 'www.example.com'],
            ['https://example.com/path', 'example.com'],
            ['https://example.com/', 'example.com'],
            ['https://example.com', 'example.com'],
            ['http://www.example.com/path', 'www.example.com'],
            ['http://product.example.com/path', 'product.example.com'],
            ['http://example.com/path', 'example.com'],
            ['http://example.com/', 'example.com'],
            ['http://example.com', 'example.com'],
        ];
    }
}
