<?php

declare(strict_types=1);

namespace App\Contexts\Product\Infrastructure\Persistence\Repository;

use App\Contexts\Crawler\Infrastructure\Persistence\Model\CrawledPagesModel;
use App\Contexts\Product\Domain\Contract\PageProductReaderInterface;
use App\Contexts\Product\Domain\ValueObject\ProductList;
use App\Contexts\Product\Infrastructure\Persistence\Mapper\ProductMapper;
use App\Contexts\Scraper\Infrastructure\Persistence\Model\ScrapedProductsModel;
use App\Shared\Domain\ValueObject\Url;
use Illuminate\Support\Facades\DB;

final readonly class PageProductReaderSQLiteRepository implements PageProductReaderInterface
{
    public function findByPage(Url $url): ProductList
    {
        $productList = ProductList::empty();
        $result = DB::table(CrawledPagesModel::TABLE_NAME)
            ->select(ScrapedProductsModel::TABLE_NAME . '.*')
            ->join(
                ScrapedProductsModel::TABLE_NAME,
                CrawledPagesModel::fromTable(CrawledPagesModel::ID),
                '=',
                ScrapedProductsModel::fromTable(ScrapedProductsModel::CRAWLED_PAGE_ID),
            )
            ->where(CrawledPagesModel::URL, $url->value)
            ->get();

        foreach ($result as $product) {
            $productList->add(ProductMapper::fromEntityArray($product));
        }

        return $productList;
    }
}
