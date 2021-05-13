<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\ConvertEmptyStringsJsonToNull;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;

class Kernel extends HttpKernel
{
	/**
	 * The application's global HTTP middleware stack.
	 *
	 * These middleware are run during every request to your application.
	 *
	 * @var array
	 */
	protected $middleware = [
		CheckForMaintenanceMode::class,
		ValidatePostSize::class,
		Middleware\TrimStrings::class,
		Middleware\Cors::class,
		ConvertEmptyStringsToNull::class,
//		ConvertEmptyStringsJsonToNull::class,
	];
	
	/**
	 * The application's route middleware groups.
	 *
	 * @var array
	 */
	protected $middlewareGroups = [
		'api' => [
			'bindings',
		],
	];
	
	/**
	 * The application's route middleware.
	 *
	 * These middleware may be assigned to groups or used individually.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth'          => Authenticate::class,
		'auth.basic'    => AuthenticateWithBasicAuth::class,
		'bindings'      => SubstituteBindings::class,
		'cache.headers' => SetCacheHeaders::class,
		'can'           => Authorize::class,
		'signed'        => ValidateSignature::class,
		'throttle'      => ThrottleRequests::class,
	];
}
