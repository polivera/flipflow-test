<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Persistence\Model;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public static function fromTable(string $fieldName): string
    {
        $model = new static();
        return sprintf("%s.%s",$model->table, $fieldName);
    }
}
