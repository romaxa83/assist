<?php

namespace App\Enums\Notes;

use App\Core\Enums\Traits\InvokableCases;

/**
 * @method static DRAFT()       // первичный статус, можно удалить
 * @method static PUBLIC()      // доступен всем
 * @method static PRIVATE()     // доступен только автору
 */

enum NoteStatus: string
{
    use InvokableCases;

    case DRAFT   = "draft";
    case PUBLIC  = "public";
    case PRIVATE = "private";
}

