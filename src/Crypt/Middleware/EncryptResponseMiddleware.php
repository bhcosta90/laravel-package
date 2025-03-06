<?php

declare(strict_types = 1);

namespace CodeFusion\src\Crypt\Middleware;

use Closure;
use Hashids\Hashids;
use Illuminate\Http\Request;

class EncryptResponseMiddleware
{
    public function __construct(protected Hashids $crypt)
    {
        //
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (env('CRYPT_ENABLE') === false) {
            return $response;
        }

        $data = json_decode($response->getContent(), true);

        if (is_array($data)) {
            $crypt = $this->crypt;

            array_walk_recursive($data, function (&$value, $key) use ($crypt) {
                if (preg_match('/^.*_id$|^id$/', (string) $key)) {
                    $value = $crypt->encode((string) $value);
                }
            });

            $response->setContent(json_encode($data));
        }

        return $response;
    }
}
