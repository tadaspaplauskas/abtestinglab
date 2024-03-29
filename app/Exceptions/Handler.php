<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        \Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Guzzle\Common\Exception\BadMethodCallException::class,
        BadMethodCallException::class,

    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(\Exception $e)
    {
        if (\App::environment('production') && $this->shouldReport($e)) // only email in production
        {
            event(new \App\Events\ExceptionThrown($e));
        }
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (!env('APP_DEBUG') && !($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException))
            return response()->view('errors.exception', [], 500);

        return parent::render($request, $e);
    }
}
