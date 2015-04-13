<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Routing\Middleware;
use Illuminate\Session\TokenMismatchException;

class CORSMiddleware implements Middleware {

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;
    }

}
