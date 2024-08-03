<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
	$middleware->web(prepend: [
            \App\Http\Middleware\EncryptCookies::class,
	    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
	    \Illuminate\Session\Middleware\StartSession::class,
	 // \Illuminate\Session\Middleware\AuthenticateSession::class,
	    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
	    \App\Http\Middleware\VerifyCsrfToken::class,
	    \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
		
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
