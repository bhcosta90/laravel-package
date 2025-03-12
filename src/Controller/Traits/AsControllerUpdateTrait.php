<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

trait AsControllerUpdateTrait
{
    abstract protected function service(): string;

    abstract protected function resource(): string;

    public function update()
    {
        $service  = app($this->service());
        $resource = $this->resource();

        $request = $this->request()[__FUNCTION__];

        try {
            $request = app($request);
            $data    = $request->validated();
            $params  = $request->route()?->parameters() ?: [];

            DB::beginTransaction();
            $response = $service->update(end($params), $data + $params);
            DB::commit();

            return new $resource($response);
        } catch (ValidationException $exception) {
            return response()->json([
                'status'    => false,
                'message'   => $exception->getMessage(),
                'errors'    => $exception->errors(),
                'validated' => $this->getRulesByRequest((new $request())->rules()),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
