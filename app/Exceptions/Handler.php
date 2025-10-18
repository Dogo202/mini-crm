<?php


namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * Список исключений, которые не репортим в логи (по желанию).
     * Можешь убрать DomainException отсюда, если хочешь логировать доменные ошибки.
     */
    protected $dontReport = [
        \App\Exceptions\DomainException::class,
    ];

    /**
     * Поля, которые не будут флэшиться обратно в форму при валидации.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        // Место для reportable-хуков, если понадобятся.
        // $this->reportable(function (Throwable $e) {});
    }

    /**
     * Единая точка рендера исключений.
     * Для API — возвращаем JSON с понятными статусами.
     */
    public function render($request, Throwable $e)
    {
        if ($request->expectsJson()) {

            // Наши доменные исключения (например, лимит 1 заявка/24ч)
            if ($e instanceof DomainException) {
                $status = (int)$e->getCode();
                if ($status < 400 || $status > 599) {
                    $status = 400;
                }
                return response()->json([
                    'message' => $e->getMessage(),
                ], $status);
            }

            // Ошибки валидации — красиво возвращаем ошибки полей
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }

            // Стандартные HTTP-исключения (404/403 и т.п.)
            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'HTTP error',
                ], $e->getStatusCode(), $e->getHeaders());
            }
        }

        return parent::render($request, $e);
    }
}
