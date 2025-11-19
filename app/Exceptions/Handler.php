<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        \App\Exceptions\CustomizeException::class,
    ];

    public function register()
    {
        $this->renderable(function (CustomizeException $e, Request $request) {
            return $e->render($request);
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'errors' => $exception->errors(),
            ], $exception->status);
        }

        if ($exception instanceof CustomizeException) {
            return $exception->render($request);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json(['error' => 'No autenticado.'], 401);
    }
}
