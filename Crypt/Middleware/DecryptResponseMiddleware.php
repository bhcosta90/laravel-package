<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Middleware;

use Closure;
use Hashids\Hashids;
use Illuminate\Http\Request;

class DecryptResponseMiddleware
{
    public function __construct(protected Hashids $crypt)
    {
        //
    }

    public function handle(Request $request, Closure $next)
    {
        if (env('CRYPT_ENABLE') === false) {
            return $next($request);
        }

        foreach ($request->route()?->parameters() as $key => $route) {
            $decoded = $this->decodeValue($request->route($key));

            if ($decoded !== null) {
                $request->route()?->setParameter($key, $decoded);
            }
        }

        $requestData = $request->all();
        $this->recursiveDecrypt($requestData);
        $request->replace($requestData);

        return $next($request);
    }

    private function recursiveDecrypt(&$data): void
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if (is_array($value)) {
                    $this->recursiveDecrypt($value); // Chamada recursiva
                } elseif (preg_match('/^.*_id$|^id$/', (string) $key)) {
                    $decoded = $this->decodeValue($value);

                    if ($decoded !== null) {
                        $value = $decoded;
                    }
                }
            }
        }
    }

    private function decodeValue($value): int | null
    {
        $decoded = $this->crypt->decode($value);

        return !empty($decoded) ? $decoded[0] : null;
    }
}
