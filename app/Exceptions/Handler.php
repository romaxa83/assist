<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable; // Для обработки исключений

class Handler extends ExceptionHandler
{
    /**
     * Список исключений, которые НЕ нужно логировать.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        // Исключения, которые не нужно логировать
    ];

    /**
     * Список входных данных, которые нельзя добавлять в дампы исключений.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Логирование или уведомление об исключении.
     *
     * @param  \Throwable  $e
     * @return void
     * @throws \Exception
     */
    public function report(Throwable $e) // Логируем исключение
    {
        parent::report($e);
    }

    /**
     * Рендерит исключение в HTTP-ответ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Custom обработка NotFoundHttpException
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Маршрут не найден',
            ], 404);
        }

        // Custom обработка ValidationException
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        }

        // AuthenticationException
        if ($e instanceof AuthenticationException) {
            return response()->json([
                'message' => 'Неавторизованное действие',
            ], 401);
        }

        // Дефолтное поведение (включает отображение ошибок при APP_DEBUG=true)
        return parent::render($request, $e);
    }
}
