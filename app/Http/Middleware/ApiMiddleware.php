<?php

namespace App\Http\Middleware;

use Closure;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Content-Range, Content-Disposition, Content-Description, X-Auth-Token');
        $response->header('Access-Control-Allow-Origin', '*');
        //add more headers here
        return $response;
        
    }
    // public function handle($request, Closure $next){
    //     $handle = $next($request);

    //     if(method_exists($handle, 'header'))
    //     {
    //         // Standard HTTP request.

    //              $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Content-Range, Content-Disposition, Content-Description, X-Auth-Token');
    //     $response->header('Access-Control-Allow-Origin', '*');

    //         return $handle;
    //     }

    //     // Download Request?

    //     // $handle->headers->set('Some-Other-Header' , 'value');

    //     return $handle;
    // } 
    
}
