<?php

namespace App\Models\Notes;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int note_id
 * @property string type
 * @property string lang
 * @property string content
 * @property int position
 * @property bool is_collapse
 *
 * @see self::note()
 * @property-read Note|BelongsTo $note
 */
class Block extends BaseModel
{
    public const TABLE = 'note_blocks';
    protected $table = self::TABLE;

    const MORPH_NAME = 'note_block';

    public $timestamps = false;

    protected $casts = [
        'is_collapse' => 'bool',
    ];

    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id');
    }
}