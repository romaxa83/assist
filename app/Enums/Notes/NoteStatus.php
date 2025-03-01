<?php
namespace App\Enums\Notes;

use App\Core\Enums\Traits\InvokableCases;
use App\Core\Enums\Traits\Label;
use App\Core\Enums\Traits\RuleIn;
use OpenAPI\Schemas\BaseScheme;

#[BaseScheme(
    resource: NoteStatus::class,
    properties: []
)]


/**
 * @method static DRAFT()       // первичный статус (присваивается при создании), можно удалить
 * @method static MODERATION()  // заметка на модерации (доступен админу)
 * @method static PUBLIC()      // доступен всем
 * @method static PRIVATE()     // доступен только автору
 */

enum NoteStatus: string
{
    use InvokableCases;
    use Label;
    use RuleIn;

    case DRAFT      = "draft";
    case MODERATION = "moderation";
    case PUBLIC     = "public";
    case PRIVATE    = "private";


    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }
    public function isModeration(): bool
    {
        return $this === self::MODERATION;
    }

    public function isPublic(): bool
    {
        return $this === self::PUBLIC;
    }
    public function isPrivate(): bool
    {
        return $this === self::PRIVATE;
    }

    public static function getStatusesForChange(NoteStatus $currentStatus)
    {
        $statuses = [];
        if($currentStatus->isDraft()){
            $statuses = [
                self::MODERATION,
            ];
        }
        if($currentStatus->isModeration()){
            $statuses = [
                self::DRAFT,
                self::PUBLIC,
                self::PRIVATE,
            ];
        }
        if($currentStatus->isPublic()){
            $statuses = [
                self::MODERATION,
                self::PRIVATE,
            ];
        }
        if($currentStatus->isPrivate()){
            $statuses = [
                self::MODERATION,
                self::PUBLIC,
            ];
        }

        $result = [];
        foreach ($statuses as $case) {
            $result[] = [
                'value' => $case->value,
                'label' => $case->label()
            ];
        }

        return $result;
    }
}