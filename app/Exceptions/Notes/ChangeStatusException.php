<?php

namespace App\Exceptions\Notes;

use Exception;
use Illuminate\Http\Response;

class ChangeStatusException extends Exception
{
    public function __construct(
        ?string $msg = null,
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR
    )
    {
        parent::__construct($msg ?? __('exceptions.notes.change_status.default'), $code);
    }
}

