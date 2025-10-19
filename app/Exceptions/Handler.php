<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * Исключения, которые можно не логировать.
     */
    protected $dontReport = [
        DomainException::class,
    ];

    /**
     * Поля, не возвращаемые назад в форму при валидации.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Регистрируем рендеры/репорты для исключений.
     */
    public function register(): void
    {
        // Доменные ошибки (лимит 24ч и т.п.) — ВСЕГДА JSON
        $this->renderable(function (\App\Exceptions\DomainException $e, $request) {
            return response()->json(['message' => $e->getMessage()], 429);
        });

        // Ошибки валидации — JSON для API/AJAX
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*') || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // Модель не найдена — JSON для API
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }
        });

        // Не аутентифицирован — JSON для API
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }
        });

        // Прочие HTTP-исключения — JSON для API
        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof HttpExceptionInterface && ($request->expectsJson() || $request->is('api/*'))) {
                return response()->json(
                    ['message' => $e->getMessage() ?: 'HTTP error'],
                    $e->getStatusCode(),
                    $e->getHeaders()
                );
            }
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof DomainException) {
            $status = (int) $e->getCode();
            if ($status < 400 || $status > 599) {
                $status = 400;
            }
            return response()->json(['message' => $e->getMessage()], $status);
        }

        return parent::render($request, $e);
    }
}
