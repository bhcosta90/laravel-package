<?php

namespace BRCas\Laravel\Http\Middleware;

use BRCas\Laravel\Services\KibanaServices;
use Closure;
use Illuminate\Http\JsonResponse;

class RegisterKibanaMiddleware
{
    protected static $kibanaService;
    private static $send = true;

    public function __construct(KibanaServices $kibanaService)
    {
        self::$kibanaService = $kibanaService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public static function handle($request, Closure $next)
    {
        $response = $next($request);

        if (self::$send == true) {
            self::$send = false;
            self::$kibanaService->logRequest($request->except(array_keys($request->query())), $request->url());
            $saveResponse = null;
            if ($response instanceof JsonResponse) {
                $saveResponse = json_decode($response->content(), true);
            }

            $params = $request->query();
            unset($params['q']);

            self::$kibanaService->addKey('request_params', $params);
            self::$kibanaService->addKey('request_method', $request->getMethod());
            if ($saveResponse) {
                self::$kibanaService->logResponse($saveResponse);
            }
            self::$kibanaService->save();
        }

        return $response;
    }
}
