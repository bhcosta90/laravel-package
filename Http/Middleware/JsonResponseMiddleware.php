<?php

namespace Costa\Package\Http\ControllersHttp\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->headers->add([
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
        ]);
        return $next($request);
    }
}
