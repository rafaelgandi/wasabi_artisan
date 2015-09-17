<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
		// LM: 09-02-2015
		// See: http://stackoverflow.com/questions/29115184/laravel-catch-tokenmismatchexception
		if ($e instanceof \Illuminate\Session\TokenMismatchException) { // If the erros is a token mismatch
			xplog('A token mismatch error happend', __METHOD__);
			/* @BOOKMARK: TODO For now just log the user out when a token mismatch happens */
			return redirect(route('logout')); 
		}
		
		// See: https://mattstauffer.co/blog/bringing-whoops-back-to-laravel-5
		if ($this->isHttpException($e)) {
			// See: https://laracasts.com/discuss/channels/requests/laravel-5-404-page-driving-me-crazy
            switch ($e->getStatusCode()) {
                case '404':
                    return \Response::view('errors.custom.404');
                break;
               /*  case '500':
                    return \Response::view('errors.custom.500');   
                break; */
                default:
                    return $this->renderHttpException($e);
                break;
            }
        }
        if (config('app.debug')) {
            return $this->renderExceptionWithWhoops($e);
        }		
        return parent::render($request, $e);
    }
	
	/**
	* Render an exception using Whoops.
	* 
	* @param  \Exception $e
	* @return \Illuminate\Http\Response
	*/
	// See: https://mattstauffer.co/blog/bringing-whoops-back-to-laravel-5
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }
}
