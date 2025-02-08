<?php

namespace App\Models\Notes;

use App\Enums\Notes\NoteStatus;
use App\ModelFilters\Notes\NoteFilter;
use App\Models\BaseModel;
use App\Models\Tags\HasTags;
use App\Models\Tags\HasTagsTrait;
use App\Models\Users\User;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int id
 * @property NoteStatus status
 * @property string title
 * @property string slug
 * @property string text
 * @property int weight
 * @property int author_id
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 *
 * @see self::author()
 * @property User|BelongsTo author
 *
 * @see Note::scopeSearch()
 * @method static Builder|Note search(string $search)
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

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopeSearch($query, string $search): void
    {
        $search = implode(' | ', array_filter(explode(' ', $search)));

        $query->selectRaw("*, ts_rank(searchable, to_tsquery('russian', ?)) as score", [$search])
            ->whereRaw("searchable @@ to_tsquery('russian', ?)", [$search])
            ->orderByRaw("ts_rank(searchable, to_tsquery('russian', ?)) desc", [$search]);
    }

    public function getMeta(User|null $user = null): array
    {
        $result = [];
        if($user){
            $result['statuses'] =  NoteStatus::getStatusesForChange($this->status, $user);
        }


        return $result;
    }
}

