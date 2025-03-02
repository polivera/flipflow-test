<?php

declare(strict_types=1);

namespace App\Contexts\Scraper\Infrastructure\Persistence\Model;

use App\Shared\Infrastructure\Persistence\Model\BaseModel;

class ScrapedProductsModel extends BaseModel
{
    public const TABLE_NAME = 'scraped_products';
    public const ID = 'scraped_product_id';
    public const CRAWLED_PAGE_ID = 'crawled_page_id';
    public const NAME = 'name';
    public const PRICE = 'price';
    public const CURRENCY_CODE = 'currency_code';
    public const PRODUCT_URL = 'product_url';
    public const IMAGE_URL = 'image_url';

    protected $primaryKey = self::ID;
    protected $table = self::TABLE_NAME;
}
