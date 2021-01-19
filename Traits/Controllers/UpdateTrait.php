<?php

namespace Costa\Package\Traits\Controllers;

use Costa\Package\Exceptions\CustomException;
use Costa\Package\Traits\BaseTrait;;
use Costa\Package\Util\ExecuteAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait UpdateTrait
{
    use BaseTrait;

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse|RedirectResponse|object
     * @throws CustomException
     */
    public function update(Request $request, $id)
    {
        return (new ExecuteAction)->setForm($this->form())
            ->setFunction($this->functionUpdate() ?: $this->getNameFunction())
            ->setId($id)
            ->setRequest($request)
            ->setService($this->service())
            ->setNameRoute($this->getNameRoute())
            ->setSession('success', __('Dados alterados com sucesso'))
            ->exec();
    }

    /**
     * @return array
     */
    protected function returnUpdateAction(): array
    {
        return [];
    }

    /**
     * @return null
     */
    public function functionUpdate()
    {
        return null;
    }
}
