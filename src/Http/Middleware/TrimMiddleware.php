<?php


namespace BRCas\Http\Middleware;

use Closure;


class TrimMiddleware
{
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        array_walk_recursive($input, function (&$item, $key) {
            if (is_string($item) && !str_contains($key, 'password')) {
                $item = trim($item);
            }
        });

        $request->merge($input);
        return $next($request);
    }
}
