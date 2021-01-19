<?php

namespace Costa\Package\Traits\Controllers;

use Costa\Package\Exceptions\CustomException;
use Costa\Package\Traits\BaseTrait;;
use Costa\Package\Util\ExecuteAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait StoreTrait
{
    use BaseTrait;

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse|object
     * @throws CustomException
     */
    public function store(Request $request)
    {
        return (new ExecuteAction)->setForm($this->form())
            ->setFunction($this->functionStore() ?: $this->getNameFunction())
            ->setRequest($request)
            ->setService($this->service())
            ->setSession('success', __('Dados inseridos com sucesso'))
            ->setNameRoute($this->getNameRoute())
            ->exec();
    }

    /**
     * @return array
     */
    protected function returnStoreAction(): array
    {
        return [];
    }

    /**
     * @return string
     * @throws CustomException
     */
    public function form(): string
    {
        throw new CustomException('Form do not implemented');
    }

    /**
     * @return string
     * @throws CustomException
     */
    public function service(): string
    {
        throw new CustomException('Service do not implemented');
    }

    /**
     * @return string
     * @throws CustomException
     */
    public function resource(): string
    {
        throw new CustomException('Resource do not implemented');
    }

    /**
     * @return null
     */
    public function functionStore(){
        return null;
    }
}
