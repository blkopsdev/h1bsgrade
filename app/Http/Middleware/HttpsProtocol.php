<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Http\Request;

class HttpsProtocol
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
        Request::setTrustedProxies([$request->getClientIp()],Request::HEADER_X_FORWARDED_ALL);
        if (!$request->secure()) {
            //$request->setTrustedProxies( [ $request->getClientIp() ] ); 
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
