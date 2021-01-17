<?php

namespace Costa\Package\Traits\Controllers;

use App\Exceptions\WebException;
use Costa\Package\Traits\BaseTrait;
use Exception;
use Illuminate\Http\Request;

trait DestroyTrait
{
    use BaseTrait;

    public function destroy(Request $request, $id)
    {
        try {
            $service = app($this->service());
            $function = $this->functionDestroy() ?: $this->getNameFunction();
            if (!method_exists($service, $function)) {
                throw new WebException(__("Method :function do not exist in service :service", [
                    'function' => $function,
                    'service' => get_class($service)
                ]));
            }

            $service->$function($id);

            switch ($request->getContentType()) {
                case 'json':
                    return response()->noContent();
                    break;
                default:
                    return redirect()->back();
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws WebException
     * @return string
     */
    public function service(): string
    {
        throw new WebException('Service do not implemented');
    }

    /**
     * @return null
     */
    public function functionDestroy()
    {
        return null;
    }
}
