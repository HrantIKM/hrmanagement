<?php

namespace App\Exceptions;

use App\Traits\Helpers\ResponseApiFunctions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    //    use ResponseApiFunctions;

    /**
     * A list of the exception types that are not reported.
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
        });
    }

    /* public function render($request, Exception|Throwable $e): \Illuminate\Http\Response|JsonResponse|Response
     {
         if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
             return $this->notFoundWithAttributeResponse();
         }

         if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
             return $this->notFoundWithAttributeResponse('route');
         }

         if (
             $e instanceof \Laravel\Sanctum\Exceptions\MissingAbilityException ||
             $e instanceof \Spatie\Permission\Exceptions\UnauthorizedException
         ) {
             return $this->forbiddenWithoutDataResponse();
         }

         if ($e instanceof \GuzzleHttp\Exception\ClientException) {
             return $this->unauthorizedWithoutDataResponse();
         }

         if ($e instanceof BadMethodCallException) {
             return $this->badRequestWithoutDataResponse();
         }

         return parent::render($request, $e);
     }*/
}
