<?php

namespace App\Models\Notes;

use App\Core\Contracts\Model\Sortable;
use App\Core\Traits\Model\Sortable as SortableTrait;
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
 * @property array text_blocks
 * @property array anchors
 * @property array links
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
class Note extends BaseModel implements HasTags, Sortable
{
    /** @use HasFactory<\Database\Factories\Notes\NoteFactory> */
    use HasFactory;
    use Filterable;
    use HasTagsTrait;
    use SortableTrait;

    public const TABLE = 'notes';
    protected $table = self::TABLE;

    public const DEFAULT_SORT_FIELD = 'created_at';

    const MORPH_NAME = 'note';

    protected $casts = [
        'status' => NoteStatus::class,
        'anchors' => 'array',
        'links' => 'array',
        'text_blocks' => 'array',
    ];

    public static function allowedSortFields(): array
    {
        return [
            'weight',
            'created_at',
        ];
    }

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




    public function canEdit(?User $user = null): array
    {
        $reason = [];
        $can = true;
        if(is_null($user)){
            $can = false;
            $reason[] = 'User is not logged in';
        }
        if($this->author_id !== $user->id){
            $can = false;
            $reason[] = 'User is not author';
        }
        if($this->status->isPublic() || $this->status->isPrivate()){
            $can = false;
            $reason[] = 'To edit a note, you need to unpublish it.';
        }

        return [
            'can' => $can,
            'reason' => $reason
        ];
    }

    public function canDelete(?User $user = null): array
    {
        $reason = [];
        $can = true;
        if(is_null($user)){
            $can = false;
            $reason[] = 'User is not logged in';
        }
        if($this->author_id !== $user->id){
            $can = false;
            $reason[] = 'User is not author';
        }
        if($this->status->isPublic() || $this->status->isPrivate()){
            $can = false;
            $reason[] = 'To delete a note, you need to unpublish it.';
        }

        return [
            'can' => $can,
            'reason' => $reason
        ];
    }


    public function canShowViaPublic(?User $user = null): array
    {
        $reason = [];
        $can = true;
        if(
            $this->status->isDraft()
            || $this->status->isModeration()
        ){
            $can = false;
            $reason[] = 'Note not published';
        }

        return [
            'can' => $can,
            'reason' => $reason
        ];
    }

    private function getWarning(): array
    {
        $result = [
            'has' => false,
            'reason' => [],
        ];

        if($this->tags->isEmpty()){
            $result['has'] = true;
            $result['reason'][] = 'The note has no attached tags';
        }

        return $result;
    }


    public function getMetaForFullPrivate(User $user): array
    {
        $result = [];
        if($user){
            $result['statuses'] =  NoteStatus::getStatusesForChange($this->status, $user);
        }

        $result['warning'] = $this->getWarning();

        return $result;
    }

    public function getMetaForSimplePrivate(User $user): array
    {
        $result = [];
        $result['actions'] = [
            'edit' => $this->canEdit($user),
            'delete' => $this->canDelete($user),
            'show' => $this->canShowViaPublic($user),
        ];
        $result['statuses'] = NoteStatus::getStatusesForChange($this->status);
        $result['warning'] = $this->getWarning();

        return $result;
    }
}

