<?php

namespace App\Models\Notes;

use App\Enums\Notes\NoteStatus;
use App\ModelFilters\Notes\NoteFilter;
use App\Models\BaseModel;
use App\Models\Tags\HasTags;
use App\Models\Tags\HasTagsTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property NoteStatus status
 * @property string title
 * @property string slug
 * @property string text
 * @property int weight
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 */
class Note extends BaseModel implements HasTags
{
    /** @use HasFactory<\Database\Factories\Notes\NoteFactory> */
    use HasFactory;
    use Filterable;
    use HasTagsTrait;

    public const TABLE = 'notes';
    protected $table = self::TABLE;

    const MORPH_NAME = 'note';

    protected $casts = [
        'status' => NoteStatus::class,
    ];

    public function modelFilter(): string
    {
        return NoteFilter::class;
    }
}

