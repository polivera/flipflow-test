<?php

declare(strict_types=1);

namespace App\Contexts\Crawler\Infrastructure\Persistence\Model;

use App\Shared\Infrastructure\Persistence\Model\BaseModel;

final class PageConfigModel extends BaseModel
{
    public const TABLE_NAME = 'page_configs';
    public const ID = 'id';
    public const SITE = 'site';
    public const PRIORITY = 'priority';
    public const HEADERS = 'headers';
    public const COOKIES = 'cookies';

    public const DEFAULT_CONFIG_SITE = "*";

    protected $table = self::TABLE_NAME;
    protected $primaryKey = self::ID;
}
