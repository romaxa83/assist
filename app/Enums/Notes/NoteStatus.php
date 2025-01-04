<?php

namespace App\Enums\Notes;

use App\Core\Enums\Traits\InvokableCases;

/**
 * @method static Draft()
 * @method static Published()
 */

enum NoteStatus: string
{
    use InvokableCases;

    case Draft      = "draft";
    case Published  = "published";
}

