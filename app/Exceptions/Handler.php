<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
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
    public function render($request, Exception $exception)
    {
        // return parent::render($request, $exception);
        return $this->apiException($request, $exception);
    }

    public function apiException($request, Exception $exception)
    {
        // dd($exception);
        // dd($exception->getMessage());
        $message = $exception->getMessage();
        $errors = null;
        $status = 500;

        if (method_exists($exception, 'getStatusCode')) {
            $status = $exception->getStatusCode();
        }

        if($exception instanceof ValidationException) {
            $errors = $exception->errors();
        }

        $exceptions = [
            'message' => $message,
            'status' => $status,
            'request' => $request->all(),
            'errors' => $errors,
            'stack_trace' => [
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'class' => get_class($exception),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ]
        ];
        $this->log($exceptions);
        return response()->json(['meta' => $exceptions]);
    }


    private function log($exceptions)
    {
        $errorLog = "";
        foreach ($exceptions as $key => $exception) {
            $strException = is_array($exception) ? json_encode($exception) : $exception;
            $message = "{$key}: {$strException}";
            if($key == 'message') {
                $message = $strException;
            }
            $errorLog .= $message . "\n";
        }
        Log::error($errorLog);
    }
}
