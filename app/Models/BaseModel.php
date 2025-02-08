<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
abstract class BaseModel extends Model
{
    public const DEFAULT_PER_PAGE = 10;
    public const DEFAULT_SORT_TYPE = 'desc';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
