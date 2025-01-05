<?php

namespace App\Models\Tags;

use App\Collections\TagCollection;
use App\ModelFilters\Tags\TagFilter;
use App\Models\BaseModel;
use EloquentFilter\Filterable;
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
    use Filterable;

    public const TABLE = 'tags';
    protected $table = self::TABLE;

    public function modelFilter(): string
    {
        return TagFilter::class;
    }

    public function newCollection(array $models = []): TagCollection
    {
        return TagCollection::make($models);
    }

    public function increaseWeight(): void
    {
        $this->weight++;
        $this->save();
    }

    public function decreaseWeight(): void
    {
        $this->weight--;
        $this->save();
    }
}

