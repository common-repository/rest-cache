<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
		// NB: This is not a very graceful place to handle this, and the manner it is handled in isn't ideal. Figure out how to make internal calls from WordPress without 404ing
		if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException && defined('REST_CACHE_HIT_RECORD_HASH'))
		{
			\App\Record::where("hash", REST_CACHE_HIT_RECORD_HASH)->increment("hits", 1);
			exit;
		}

        return parent::render($request, $exception);
    }
}
