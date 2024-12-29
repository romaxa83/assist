<?php

namespace App\Exceptions;

use App\Http\Controllers\ApiController;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions as BaseExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Response as HttpResponse;

class Handler
{
    protected int $jsonFlags = JSON_UNESCAPED_SLASHES ^ JSON_UNESCAPED_UNICODE;

    public function __invoke(BaseExceptions $exceptions): BaseExceptions
    {
//        dd($exceptions);
        $this->renderUnauthorized($exceptions);
        $this->renderNotFound($exceptions);
        $this->renderValidation($exceptions);
        $this->renderError($exceptions);


        return $exceptions;
    }

    protected function renderUnauthorized(BaseExceptions $exceptions): void
    {
        $exceptions->renderable(
            fn (AuthenticationException $e, ?Request $request = null) => $this->response(
                message: __('Unauthorized'),
                code: HttpResponse::HTTP_UNAUTHORIZED,
                asJson: $request?->expectsJson() ?? false
            )
        );
    }

    protected function renderNotFound(BaseExceptions $exceptions): void
    {
        $exceptions->renderable(
            fn (NotFoundHttpException $e, ?Request $request = null) => $this->response(
                message: __('Not Found'),
                code: HttpResponse::HTTP_NOT_FOUND,
                asJson: $request?->expectsJson() ?? false
            )
        );
    }

    protected function renderValidation(BaseExceptions $exceptions): void
    {
        $exceptions->renderable(
            fn (ValidationException $e, ?Request $request = null) => $this->response(
                message: $e->getMessage(),
                code: HttpResponse::HTTP_UNPROCESSABLE_ENTITY,
                asJson: $request?->expectsJson() ?? false,
                errors: $e->errors()
            )
        );
    }

    protected function renderError(BaseExceptions $exceptions): void
    {
        $exceptions->renderable(
            fn (\Error $e, ?Request $request = null) => $this->response(
                message: $e->getMessage(),
                code: HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
                asJson: $request?->expectsJson() ?? false
            )
        );
    }

    protected function response(
        string $message,
        int $code,
        bool $asJson,
        array $errors = []
    ): Response
    {
        if ($asJson) {
            if($code === HttpResponse::HTTP_UNPROCESSABLE_ENTITY){
                return ApiController::errorJsonValidation($message, $errors);
            }

            return ApiController::errorJsonMessage($message, $code);
        }

        $this->registerErrorViewPaths();

        return response()->view($this->view($code), status: $code);
    }

    protected function view(int $code): string
    {
        return view()->exists('errors::' . $code) ? 'errors::' . $code : 'errors::400';
    }

    protected function registerErrorViewPaths(): void
    {
        View::replaceNamespace(
            'errors',
            collect(config('view.paths'))
                ->map(fn (string $path) => "$path/errors")
                ->push($this->vendorViews())
                ->all()
        );
    }

    protected function vendorViews(): string
    {
        return __DIR__ . '/../../vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/views';
    }
}
