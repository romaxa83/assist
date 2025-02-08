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
 * @method static MODERATED()   // заметка промодерирована (после чего владелец может сменить статус)
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
    case MODERATED  = "moderated";
    case PUBLIC     = "public";
    case PRIVATE    = "private";
}