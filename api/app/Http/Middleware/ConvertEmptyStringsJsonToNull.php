<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConvertEmptyStringsJsonToNull
{
	public function handle(Request $request, Closure $next) {
		$json = $request->json()->all();
		
//		self::checkEmptyString($json);
//
//		$request->json()->replace($json);
		
		return $next($request);
	}
	
	protected static function checkEmptyString(&$input): void {
		if (is_array($input)) {
			$keys = array_keys($input);
			
			foreach ($keys as $key) {
				if (is_array($input[$key]) || is_object($input[$key])) {
					self::checkEmptyString($input[$key]);
					
					continue;
				}
				
				if ($input[$key] === '') {
					$input[$key] = NULL;
				}
			}
		}
		else {
			$keys = get_object_vars($input);
			
			foreach ($keys as $key) {
				if (is_array($input->{$key}) || is_object($input->{$key})) {
					self::checkEmptyString($input->{$key});
					
					continue;
				}
				
				if ($input->{$key} === '') {
					$input->{$key} = NULL;
				}
			}
		}
	}
}
