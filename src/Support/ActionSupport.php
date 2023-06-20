<?php

namespace BRCas\Laravel\Support;

use Collective\Html\FormFacade;

class ActionSupport
{
    protected array $data = [];

    public function name(string $name)
    {
        $this->data['name'] = [
            'name' => $name,
        ];
        return $this;
    }

    public function text(string $text)
    {
        $this->data['text'] = [
            'text' => $text,
        ];
        return $this;
    }

    public function url(string $route, array $params = [])
    {
        $this->data['url'] = [
            'route' => $route,
            'params' => $params,
        ];
        return $this;
    }

    public function ajax($ajax = true)
    {
        $this->data['ajax'] = $ajax;
        return $this;
    }

    public function generateForm(
        string $route,
        string $form,
        string $model = null,
        string $template = null,
    ) {
        $this->data['generateForm'] = [
            'route' => $route,
            'form' => $form,
            'title' => $this->data['text']['text'],
            'model' => $model,
            'template' => $template,
        ];
        return $this;
    }

    public function form(string $route, string $name, string $method = null)
    {
        $this->data['form'] = [
            'route' => $route,
            'name' => $name,
            'method' => $method,
        ];
        return $this;
    }

    public function btn(string $class)
    {
        $this->data['btn'] = [
            'class' => $class,
        ];
        return $this;
    }

    public function getName()
    {
        return $this->data['name']['name'];
    }

    public function run(array $data = [])
    {
        $html = "";
        $url = $this->data['url'] ?? "javascript:void(-1)";
        $routeName = "";

        if (!empty($url)) {
            $params = [];
            foreach (($url['params'] ?? []) as $k => $param) {
                if (is_string($k)) {
                    $params[$k] = $param;
                } else {
                    $params[] = $data[$param];
                }
            }
            $routeName = $url['route'];
            $url = route($url['route'], $params);
        }

        if ($url) {
            $id = "";

            if (!empty($this->data['form'])) {
                $methodForm = $this->data['form']['method'] ?? ($this->isDelete() ? "DELETE" : "POST");

                $html .= FormFacade::open([
                    'url' => $url,
                    'id' => $id = "frm-" . sha1($url . $methodForm),
                    'method' => $methodForm,
                    'style' => 'display:none;',
                    'class' => 'form-delete-confirmation',
                ]);
                $html .= "<button>{$id}</button>";
                $html .= FormFacade::close();
            }

            if (!empty($this->data['generateForm'])) {
                $url = route($this->data['generateForm']['route'], [
                    'model' => base64_encode($this->data['generateForm']['model']),
                    'template' => base64_encode($this->data['generateForm']['template']),
                    'form' => base64_encode($this->data['generateForm']['form']),
                    'destination' => base64_encode($url),
                    'title' => base64_encode($this->data['generateForm']['title']),
                ] + $params);
            }

            $btnClass = $this->data['btn']['class'] ?? "btn-outline-secondary ";

            switch ($this->data['name']['name'] ?? $this->data['text']['text']) {
                case 'edit':
                    $this->data['text']['text'] = null;
                    $this->data['text']['icon'] = "fa fa-edit";
                    break;
                case 'show':
                    $this->data['text']['text'] = null;
                    $this->data['text']['icon'] = "fa fa-info-circle";
                    break;
                case 'destroy':
                case 'delete':
                    $this->data['text']['text'] = null;
                    $this->data['text']['icon'] = "fa-regular fa-trash-can";
                    $btnClass .= " btn-frm-remove ";
                    break;
            }

            $ajax = empty($this->data['ajax']) ? "" : "btn-ajax ";
            $html .= "<a href='{$url}' data-id='{$id}' data-url='{$routeName}' class='btn btn-sm {$btnClass} {$ajax}'>";

            $html .= $this->data['text']['text'];
            if (!empty($icon = $this->data['text']['icon'] ?? null)) {
                $html .= "<i class='{$icon}'></i>";
            }
            $html .= "</a>";
        }

        return $html;
    }

    protected function isDelete()
    {
        return in_array($this->getName(), ['delete', 'destroy']);
    }
}
