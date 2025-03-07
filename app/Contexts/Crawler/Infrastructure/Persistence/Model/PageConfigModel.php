<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Model;

use App\Shared\Infrastructure\Persistence\Model\BaseModel;

/**
 * @method static where(\Closure $param)
 */
final class PageConfigModel extends BaseModel
{
    public const TABLE_NAME = 'page_configs';
    public const ID = 'id';
    public const SITE = 'site';
    public const PRIORITY = 'priority';
    public const HEADERS = 'headers';
    public const COOKIES = 'cookies';

    public const DEFAULT_CONFIG_SITE = "*";
    public const EMPTY_HEADERS_JSON = '{}';
    public const EMPTY_COOKIES_JSON = '{}';

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::ID;
}
