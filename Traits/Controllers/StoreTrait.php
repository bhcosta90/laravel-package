<?php

namespace Costa\Package\Traits\Controllers;

use App\Exceptions\WebException;
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
     * @throws WebException
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
     * @throws WebException
     */
    public function form(): string
    {
        throw new WebException('Form do not implemented');
    }

    /**
     * @return string
     * @throws WebException
     */
    public function service(): string
    {
        throw new WebException('Service do not implemented');
    }

    /**
     * @return string
     * @throws WebException
     */
    public function resource(): string
    {
        throw new WebException('Resource do not implemented');
    }

    /**
     * @return null
     */
    public function functionStore(){
        return null;
    }
}
