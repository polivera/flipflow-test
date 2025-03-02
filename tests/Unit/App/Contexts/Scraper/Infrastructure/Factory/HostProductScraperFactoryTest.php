<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Scraper\Infrastructure\Factory;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperInterface;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;
use App\Contexts\Scraper\Infrastructure\Exception\HostProductScraperFactoryException;
use App\Contexts\Scraper\Infrastructure\Factory\HostProductScraperFactory;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Config;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\DomainStub;
use Tests\Stubs\Shared\ValueObject\ScrapedContentListStub;

#[CoversClass(HostProductScraperFactory::class)] final class HostProductScraperFactoryTest extends TestCase
{
    private Container|MockInterface $container;
    private HostProductScraperFactory $factory;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = Mockery::mock(Container::class);
        $this->factory = new HostProductScraperFactory($this->container);
    }

    public function testScraperConfigNotFound(): void
    {
        $mockDomain = DomainStub::random();
        Config::shouldReceive('get')
            ->with('scrapers.product', [])
            ->once()
            ->andReturn(null);

        $this->expectException(HostProductScraperFactoryException::class);
        $this->expectexceptionMessage("Scraper class map configuration not found");

        $this->factory->createForDomain($mockDomain);
    }

    public function testScraperConfigReturnMapWithoutNeededEntry(): void
    {
        $mockDomain = DomainStub::random();
        $mockScraperClassName = "SomeScraperProductClass";
        Config::shouldReceive('get')
            ->with('scrapers.product', [])
            ->once()
            ->andReturn(['some-domain' => $mockScraperClassName]);

        $this->expectException(HostProductScraperFactoryException::class);
        $this->expectexceptionMessage("ProductScrapper class for domain $mockDomain->value not found");

        $this->factory->createForDomain($mockDomain);
    }

    public function testScraperClassCannotBeBind(): void
    {
        $mockDomain = DomainStub::random();
        $mockScraperClassName = "SomeScraperProductClass";
        $mockException = new BindingResolutionException("Binding error");
        Config::shouldReceive('get')
            ->with('scrapers.product', [])
            ->once()
            ->andReturn([$mockDomain->value => $mockScraperClassName]);

        $this->container
            ->shouldReceive('make')
            ->once()
            ->with($mockScraperClassName)
            ->andThrow($mockException);

        $this->expectException(HostProductScraperFactoryException::class);
        $this->expectexceptionMessage("Cannot bind class $mockScraperClassName for domain $mockDomain->value");

        $this->factory->createForDomain($mockDomain);
    }

    public function testHappyPath(): void
    {
        $mockDomain = DomainStub::random();
        $mockScraperClassName = "SomeScraperProductClass";
        $mockResult = new MockResultClass();
        Config::shouldReceive('get')
            ->with('scrapers.product', [])
            ->once()
            ->andReturn([$mockDomain->value => $mockScraperClassName]);

        $this->container
            ->shouldReceive('make')
            ->once()
            ->with($mockScraperClassName)
            ->andReturn($mockResult);

        $result = $this->factory->createForDomain($mockDomain);

        $this->assertInstanceOf(MockResultClass::class, $result);
    }
}

final readonly class MockResultClass implements HostProductScraperInterface
{
    public function scrapProducts(CrawledPage $crawledPage): ScrapedContentList
    {
        return ScrapedContentListStub::random();
    }
}
