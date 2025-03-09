<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Middleware;

use Closure;
use Hashids\Hashids;
use Illuminate\Http\Request;

class CryptMiddleware
{
    public function __construct(protected Hashids $crypt)
    {
        //
    }

    public function handle(Request $request, Closure $next)
    {
        if (config('hashids.enable') === true) {
            foreach ($request->route()?->parameters() as $key => $route) {
                $decoded = $this->decodeValue($request->route($key));

                if ($decoded !== null) {
                    $request->route()?->setParameter($key, $decoded);
                }
            }

            $requestData = $request->all();
            $this->recursiveDecrypt($requestData);
            $request->replace($requestData);
        }

        $response = $next($request);

        if (config('hashids.enable') === false) {
            return $response;
        }

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (is_array($data)) {
            $crypt = $this->crypt;

            array_walk_recursive($data, function (&$value, $key) use ($crypt) {
                if (preg_match('/^.*_id$|^id$/', (string) $key)) {
                    $value = $crypt->encode((string) $value);
                }
            });

            $response->setContent(json_encode($data, JSON_THROW_ON_ERROR));
        }

        return $response;
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
