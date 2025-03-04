<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Model;

use App\Shared\Infrastructure\Persistence\Model\BaseModel;

class CrawledPagesModel extends BaseModel
{
    public const TABLE_NAME = 'crawled_pages';
    public const ID = 'crawled_page_id';
    public const DOMAIN = 'domain';
    public const URL = 'url';
    public const CONTENT = 'content';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::ID;
}
