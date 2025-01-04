<?php

namespace App\Models\Notes;

use App\Enums\Notes\NoteStatus;
use App\ModelFilters\Notes\NoteFilter;
use App\Models\BaseModel;
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
class Note extends BaseModel
{
    /** @use HasFactory<\Database\Factories\Notes\NoteFactory> */
    use HasFactory;
    use Filterable;

    public const TABLE = 'notes';
    protected $table = self::TABLE;

    protected $casts = [
        'status' => NoteStatus::class,
    ];

    public function modelFilter(): string
    {
        return NoteFilter::class;
    }
}

