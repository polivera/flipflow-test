<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Model;

class CrawledPagesModel extends Model
{
    public const TABLE_NAME = 'crawled_pages';
    public const ID = 'crawled_page_id';
    public const DOMAIN = 'domain';
    public const URL = 'url';
    public const CONTENT = 'content';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $primaryKey = self::ID;
    protected $table = self::TABLE_NAME;
}
