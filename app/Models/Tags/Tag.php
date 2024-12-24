<?php

namespace App\Models\Tags;


use App\Models\BaseModel;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property string name
 * @property string|null color
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 */
class Tag extends BaseModel
{
    public const TABLE = 'tags';
    protected $table = self::TABLE;
}

