<?php

declare(strict_types = 1);

namespace CodeFusion\Controller\Traits;

use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\DB;

trait AsControllerDeleteTrait
{
    abstract protected function service(): string;

    public function destroy(Request $request): Response
    {
        $service = app($this->service());

        $params = $request->route()?->parameters() ?: [];

        try {
            DB::beginTransaction();
            $service->destroy(end($params), $params);
            DB::commit();

            return response()->noContent();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
