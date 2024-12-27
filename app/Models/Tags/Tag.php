<?php

namespace App\Models\Tags;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property string name
 * @property string slug
 * @property int weight
 * @property string|null color
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 */
class Tag extends BaseModel
{
    /** @use HasFactory<\Database\Factories\Tags\TagFactory> */
    use HasFactory;

    public const TABLE = 'tags';
    protected $table = self::TABLE;
}

