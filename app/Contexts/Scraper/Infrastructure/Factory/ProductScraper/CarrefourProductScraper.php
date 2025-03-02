<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Factory\ProductScraper;

use App\Contexts\Crawler\Domain\ValueObject\CrawledPage;
use App\Contexts\Scraper\Domain\Contract\HostProductScraperInterface;
use App\Contexts\Scraper\Domain\ValueObject\ProductName;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedProduct;
use App\Shared\Domain\ValueObject\Price;
use App\Shared\Domain\ValueObject\Url;
use DOMDocument;
use DOMXPath;

final readonly class CarrefourProductScraper implements HostProductScraperInterface
{
    public function scrapProducts(CrawledPage $crawledPage): ScrapedContentList
    {
        $productListXPath = "//ul[contains(@class, 'product-card-list__list')]" .
            "/li[contains(@class, 'product-card-list__item')]";

        $domHtml = new DOMDocument('1.0', 'UTF-8');
        @$domHtml->loadHTML($crawledPage->content->body);

        $xpath = new DOMXPath($domHtml);
        $elements = $xpath->query($productListXPath);

        $result = ScrapedContentList::empty();
        foreach ($elements as $element) {
            $productLinkXpath = ".//a[contains(@class, 'product-card__title-link')]";
            $productPriceXpath = ".//span[contains(@class, 'product-card__price')]";
            $productImageXpath = ".//img[contains(@class, 'product-card__image')]";

            $currentElement = new DOMXPath($element->ownerDocument);

            $elementLink = $currentElement->evaluate($productLinkXpath, $element);
            $nameStr = trim($elementLink->item(0)?->textContent ?? '');
            if (empty($nameStr)) {
                continue;
            }
            $urlStr = $elementLink->item(0)?->attributes->getNamedItem('href')->nodeValue ?? '';

            $elementPrice = $currentElement->evaluate($productPriceXpath, $element);
            $priceStr = trim($elementPrice->item(0)?->textContent ?? '');

            $elementImage = $currentElement->evaluate($productImageXpath, $element);
            $imgUrlStr = $elementImage->item(0)?->attributes->getNamedItem('src')->nodeValue ?? '';

            $result->add(ScrapedProduct::createWithoutID(
                $crawledPage->id,
                ProductName::create($nameStr),
                Price::fromString($priceStr),
                Url::create($imgUrlStr),
                // TODO: How do I bring the proto here
                Url::create('https://' . $crawledPage->domain->value . $urlStr),
            ));
        }

        return $result;
    }
}
