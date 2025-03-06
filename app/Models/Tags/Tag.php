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
 * @property int public_attached    // кол-во привязанных публичных заметок
 * @property int private_attached   // кол-во привязанных публичных и приватных заметок
 * @property string|null color
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 */
class Tag extends BaseModel
{
    /** @use HasFactory<\Database\Factories\Tags\TagFactory> */
    use HasFactory;
    use Filterable;

    public const DEFAULT_SORT_FIELD = 'private_attached';

    public const TABLE = 'tags';
    protected $table = self::TABLE;

    protected $fillable = [
        'name',
        'color'
    ];

    public function modelFilter(): string
    {
        return TagFilter::class;
    }

    public function newCollection(array $models = []): TagCollection
    {
        return TagCollection::make($models);
    }

    public function increasePublicAttached(bool $save = true): void
    {
        $this->public_attached++;
        if ($save)  $this->save();
    }
    public function decreasePublicAttached(bool $save = true): void
    {
        $this->public_attached--;
        if ($save)  $this->save();
    }

    public function increasePrivateAttached(bool $save = true): void
    {
        $this->private_attached++;
        if ($save)  $this->save();
    }
    public function decreasePrivateAttached(bool $save = true): void
    {
        $this->private_attached--;
        if ($save)  $this->save();
    }
}