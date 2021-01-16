<?php

namespace Costa\Package\Http\Controllers;

use Illuminate\Support\Facades\Route;

trait BaseTrait
{
    /**
     * @return string|null
     */
    protected function getNameRouteWithAction(): ?string
    {
        return Route::currentRouteName();
    }

    /**
     * @return string|null
     */
    protected function getView(): ?string
    {
        return $this->getNameRoute();
    }

    /**
     * @param null $route
     * @return string|null
     */
    protected function getNameRoute($route = null): ?string
    {
        if ($route === null) {
            $route = $this->getNameRouteWithAction();
        }

        $route = explode('.', $route);
        array_pop($route);
        return implode('.', $route);
    }

    /**
     * @param null $route
     * @return string
     */
    protected function getUcWords($route = null): string
    {
        if ($route === null) {
            $route = $this->getNameRouteWithAction();
        }
        $replaceDot = str_replace('.', ' ', $route);
        $replaceUcWord = ucwords($replaceDot);
        $replaceSpace = str_replace(' ', '', $replaceUcWord);
        return (string)$replaceSpace;
    }

    /**
     * @param null $ucWord
     * @return string
     */
    protected function getNameFunction($ucWord = null): string
    {
        if ($ucWord == null) {
            $ucWord = $this->getUcWords();
        }
        return lcfirst($ucWord);
    }

    /**
     * @return null
     */
    protected function verifyContract()
    {
        return null;
    }
}
