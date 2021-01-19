<?php


namespace Costa\Package\Util;


use Costa\Package\Exceptions\CustomException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\FormBuilder;

class ExecuteAction
{
    private $form;

    private $request;

    private $service;

    private $function;

    private $id;

    private $nameRoute;

    private $sessionMessage;

    private $sessionKey;

    /**
     * @param mixed $sessionKey
     * @param mixed $sessionMessage
     * @return ExecuteAction
     */
    public function setSession($sessionKey, $sessionMessage)
    {
        $this->sessionKey = $sessionKey;
        $this->sessionMessage = $sessionMessage;
        return $this;
    }

    /**
     * @param mixed $nameRoute
     * @return ExecuteAction
     */
    public function setNameRoute($nameRoute)
    {
        $this->nameRoute = $nameRoute;
        return $this;
    }

    /**
     * @param mixed $form
     * @return ExecuteAction
     */
    public function setForm($form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @param mixed $request
     * @return ExecuteAction
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @param mixed $service
     * @return ExecuteAction
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @param mixed $function
     * @return ExecuteAction
     */
    public function setFunction($function)
    {
        $this->function = $function;
        return $this;
    }

    /**
     * @param mixed $id
     * @return ExecuteAction
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return JsonResponse|RedirectResponse|object
     * @throws CustomException
     */
    public function exec($return = null)
    {
        try {
            $formBuilder = app(FormBuilder::class);

            $form = $formBuilder->create($this->form);

            if (!$form->isValid()) {
                switch ($this->request->getContentType()) {
                    case 'json':
                        return response()->json($form->getErrors())->setStatusCode(Response::HTTP_NOT_ACCEPTABLE);
                    default:
                        return redirect()->back()->withErrors($form->getErrors())->withInput();
                }
            }

            $service = app($this->service);
            $function = $this->function;
            if (!method_exists($service, $function)) {
                throw new CustomException(__("Method :function do not exist in service :service", [
                    'function' => $function,
                    'service' => get_class($service)
                ]));
            }
            $data = $form->getFieldValues();
            if ($this->id) {
                $ret = $service->$function($this->id, $data);
            } else {
                $ret = $service->$function($data);
            }

            switch ($this->request->getContentType()) {
                case 'json':
                    dd('Configura aqui');
                    break;
                default:
                    if ($this->sessionKey && $this->sessionMessage) {
                        session()->flash($this->sessionKey, $this->sessionMessage);
                    }
                    if ($return === null) {
                        return redirect()->route($this->nameRoute . '.index');
                    } else {
                        return $return($ret);
                    }
            }
        } catch (CustomException $e) {
            if ($e->getCode() > 0) {
                switch ($this->request->getContentType()) {
                    case 'json':
                        return response()->json(['message' => $e->getMessage()])->setStatusCode($e->getCode());
                    default:
                        session()->flash($e->getTypeError(), __($e->getMessage()));
                        return redirect()->back();
                }
            }
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }
}
