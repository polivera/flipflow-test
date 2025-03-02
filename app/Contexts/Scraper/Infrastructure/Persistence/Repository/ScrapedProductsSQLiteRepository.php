<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Persistence\Repository;

use App\Contexts\Scraper\Domain\Contract\ScrapedProductsRepositoryInterface;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedContentList;
use App\Contexts\Scraper\Domain\ValueObject\ScrapedProduct;
use App\Contexts\Scraper\Infrastructure\Persistence\Mapper\ScrapedProductMapper;
use App\Contexts\Scraper\Infrastructure\Persistence\Model\ScrapedProductsModel;
use App\Shared\Domain\ValueObject\Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ScrapedProductsSQLiteRepository implements ScrapedProductsRepositoryInterface
{
    public function saveBulk(ScrapedContentList $scrapedContentList): int
    {
        $insertData = [];
        $nowDate = Date::now();
        /** @var ScrapedProduct $scrapedContent */
        foreach($scrapedContentList->iterator() as $scrapedContent) {
            $modelValues = ScrapedProductMapper::toModel($scrapedContent)->toArray();
            $insertData[] = [...$modelValues, Model::CREATED_AT => $nowDate->format(Date::DATE_AND_TIME)];
        }

        $success = DB::TABLE(ScrapedProductsModel::TABLE_NAME)->insert($insertData);

        return $success ? count($insertData) : 0;
    }
}
