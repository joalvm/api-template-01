<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\DB;
use Joalvm\Utils\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (\Throwable $e) {
        });
    }

    public function render($request, \Throwable $e)
    {
        DB::rollBack();

        if ($e instanceof AuthorizationException) {
            $e->withStatus(HttpFoundationResponse::HTTP_UNAUTHORIZED);
        }

        return Response::catch($e);
    }
}
