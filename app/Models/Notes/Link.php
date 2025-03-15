<?php

namespace App\Models\Notes;

use App\Enums\Notes\NoteStatus;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property string link                // сама ссылка
 * @property string name
 * @property array attributes           // аттрибуты добавляемые к тегу <a>
 * @property int note_id                // к какой заметки относится
 * @property int|null to_note_id        // на какую заметку ведет, если это внутренняя ссылка
 * @property bool active                // рабочая ли ссылка
 * @property bool is_external           // ведет ли ссылка на внешние ресурсы
 * @property Carbon|null last_check_at  // даты последней проверки на активность
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 * @property array reasons              // если после проверки ссылка стает не активной, здесь будет список причин почему это так
 *
 * @see self::note()
 * @property Note|BelongsTo note
 *
 * @see self::linkedNote()
 * @property Note|BelongsTo linkedNote
 */
class Link extends BaseModel
{
    /** @use HasFactory<\Database\Factories\Notes\LinkFactory> */
    use HasFactory;

    public const TABLE = 'note_links';
    protected $table = self::TABLE;

    const MORPH_NAME = 'note_link';

    protected $casts = [
        'status' => NoteStatus::class,
        'is_external' => 'bool',
        'active' => 'bool',
        'attributes' => 'array',
        'reasons' => 'array',
    ];

    /** @return BelongsTo<Note> */
    public function note(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'note_id');
    }

    /** @return BelongsTo<Note> */
    public function linkedNote(): BelongsTo
    {
        return $this->belongsTo(Note::class, 'to_note_id');
    }

    /**
     * получение как тег "a" (без содержимого и и закрывающегося тега),
     * с ссылкой и атрибутами, для замены в тексте
     */
    public function getAsTag(): string
    {
        $tag = '<a href="'. $this->link .'" ';
        foreach ($this->getAttribute('attributes') as $key => $value){
            $tag .= $key . '="' . $value . '" ';
        }
        $tag .= '>';

        return $tag;
    }
}