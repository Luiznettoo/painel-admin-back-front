<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
	private const HEADERS = [
		'Access-Control-Allow-Origin'      => '*',
		'Access-Control-Allow-Methods'     => 'POST, HEAD, GET, OPTIONS, PUT, DELETE',
		'Access-Control-Allow-Credentials' => 'true',
		'Access-Control-Max-Age'           => '86400',
		'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
	];
	
	public function handle(Request $request, Closure $next) {
		if ($request->isMethod('OPTIONS')) {
			return response(NULL, 204);
		}
		
		/*$response = $next($request);
		
		foreach (self::HEADERS as $k => $v) {
			$response->headers->set($k, $v);
		}
		
		return $response;*/

		return $next($request);
	}
}
