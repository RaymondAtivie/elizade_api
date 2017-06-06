<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
     public function render($request, Exception $e)
    {
        if ($e instanceof ErrorException) {
            return response()->json([
                "success" => false,
                "type" => "error",
                "message" => "An Error occured"], 
            404);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                "success" => false,
                "type" => "invalid_method",
                "message" => "This method is not supported on this endpoint"], 
            404);
        }
        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                "success" => false,
                "type" => "invalid_endpoint",
                "message" => "This endpoint is not supported and does not exist"], 
            404);
        }

        if($e instanceof \App\Exceptions\JWT\InvalidUserException) {
            return response()->json(['success'=>false, 'type'=>'login_failed', 'message'=>$e->getMessage()], 422);
        } 

        if($e instanceof \App\Exceptions\JWT\TokenException) {
            switch($e->getMessage()){
                case 'invalid_token':
                    $message = "Invalid token.";
                break;
                case 'invalid_user':
                    $message = "Invalid token.";
                break;
                case 'token_expired':
                    $message = "Your token has expired.";
                break;
                case 'invalid_alg':
                    $message = "Your algorithm is invalid.";
                break;
                case 'token_absent':
                    $message = "No token supplied.";
                break;
                default:
                    $message = "";
                break;
            }
            return response()->json(['success'=>false, 'type'=>$e->getMessage(), 'message'=>$message], 401);
        }
        
        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
