<?php

if (!function_exists('action_support')) {
    function action_support(): \BRCas\Laravel\Support\ActionSupport
    {
        return new \BRCas\Laravel\Support\ActionSupport();
    }
}
