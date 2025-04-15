<?php

declare(strict_types = 1);

namespace CodeFusion\Crypt\Middleware;

use Closure;
use CodeFusion\Crypt\Facade\HashId;
use Illuminate\Http\Request;

class CryptMiddleware
{
    protected HashId $crypt;

    public function __construct()
    {
        $this->crypt = app(HashId::class);
    }

    public function handle(Request $request, Closure $next)
    {
        if (config('hashids.enable') === true) {
            foreach ($request->route()?->parameters() as $key => $route) {
                if (is_string($key)) {
                    $decoded = $this->crypt::decode($request->route($key));

                    if ($decoded !== null) {
                        $request->route()?->setParameter($key, $decoded);
                    }
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

        $data = json_decode((string) $response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (is_array($data)) {

            array_walk_recursive($data, function (&$value, $key): void {
                if (is_numeric($value) && $this->crypt::verify((string) $key)) {
                    $value = $this->crypt::encode((string) $value);
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
                } elseif ($this->crypt::verify((string) $key)) {
                    $decoded = $this->crypt::decode($value);

                    if ($decoded !== null) {
                        $value = $decoded;
                    }
                }
            }
        }
    }
}
