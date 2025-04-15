<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use CodeFusion\Controller\Traits\Helper\AsAddRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait AsControllerStoreTrait
{
    use AsAddRequest;

    abstract protected function service(): string;

    abstract protected function resource(): string;

    public function store()
    {
        $service  = app($this->service());
        $resource = $this->resource();

        $request = $this->request()[__FUNCTION__];

        try {
            $request = app($request);
            $data    = $request->validated();
            $params  = $request->route()?->parameters() ?: [];

            return DB::transaction(function () use ($service, $params, $data, $resource): object {
                $response = $service->store($data + $params);

                return new $resource($response);
            });

        } catch (ValidationException $exception) {
            return response()->json([
                'status'  => false,
                'message' => $exception->getMessage(),
                'errors'  => $exception->errors(),
                'rules'   => $this->getRulesByRequest((new $request())->rules()),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
