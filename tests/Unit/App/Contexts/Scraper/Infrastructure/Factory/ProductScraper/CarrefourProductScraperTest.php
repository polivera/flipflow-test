<?php

declare(strict_types=1);

namespace Tests\Unit\App\Contexts\Scraper\Infrastructure\Factory\ProductScraper;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Crawler\Domain\ValueObject\PageContent;
use App\Contexts\Scraper\Infrastructure\Factory\ProductScraper\CarrefourProductScraper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\Context\Crawler\ValueObject\DomainStub;
use Tests\Stubs\Shared\ValueObject\NumberIDStub;
use Tests\Stubs\Shared\ValueObject\UrlStub;

#[CoversClass(CarrefourProductScraper::class)] final class CarrefourProductScraperTest extends TestCase
{
    private CarrefourProductScraper $scraper;

    public function setUp(): void
    {
        parent::setUp();
        $this->scraper = new CarrefourProductScraper();
    }

    public function testScrapProducts()
    {
        $mockPrice = '20,3 €';
        $mockImgLink = 'https://example.local/image.jpg';
        $mockProductLink = '/my/mock/product/relative/link';
        $mockProductName = "This is a mock product name";
        $mockID = NumberIDStub::random();
        $mockDomain = DomainStub::random();
        $crawledPage = CrawledPage::create(
            id: $mockID,
            domain: $mockDomain,
            url: UrlStub::random(),
            content: PageContent::create(
                $this->getPageContent($mockPrice, $mockImgLink, $mockProductLink, $mockProductName)
            ),
        );

        $result = $this->scraper->scrapProducts($crawledPage);

        $this->assertEquals(1, $result->length());
        $this->assertEquals("203", $result->get(0)->price->value);
        $this->assertEquals("€", $result->get(0)->price->currency);
        $this->assertEquals($mockID->value, $result->get(0)->crawledPageID->value);
        $this->assertEquals($mockProductName, $result->get(0)->productName->value);
        $this->assertEquals($mockImgLink, $result->get(0)->imageUrl->value);
        $this->assertEquals("https://" . $mockDomain->value . $mockProductLink, $result->get(0)->productUrl->value);
    }

    private function getPageContent(
        string $price,
        string $imgLink,
        string $productLink,
        string $name,
        string $altPrice = "3,14 €/kg"
    ): string {
        return <<<HTML
            <ul class="product-card-list__list">
    <li class="product-card-list__item" >
        <div  class="product-card__parent" >
            <div class="product-card" >
                <div class="product-card__media" >
                    <a href="$productLink"  class="product-card__media-link track-click">
                        <img src="$imgLink" alt="$name" class="product-card__image" >
                    </a>
                </div>
                <div class="product-card__info-container" >
                    <div class="product-card__detail" >
                        <div class="product-card__prices-container" >
                            <div class="product-card__prices" >
                                <span class="product-card__price" >
                $price
              </span>
                            </div>
                            <div class="product-card__price-per-unit--container" >
                                <div class="product-card__price-per-unit--strikethrough-container" >
                                    <span class="product-card__price-per-unit" >
                $altPrice
              </span>
                                </div>
                            </div>
                        </div>
                        <h2 class="product-card__title" >
                            <a href="$productLink"  class="product-card__title-link track-click">
            $name
          </a>
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
HTML;
    }

}
