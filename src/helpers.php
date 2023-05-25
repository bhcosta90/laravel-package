<?php

if (!function_exists('title')) {
    function title(string $title)
    {
        return "<h5 class='pt-1 pb-0 title'>" . __($title) . "</h5>";
    }
}

if (!function_exists('is_active')) {
    function is_active(string $title, string $url, bool $active)
    {
        $btn = "text-success";
        $icon = "fas fa-check-square";
        $title = __('Disable ' . $title);

        if (empty($active)) {
            $btn = "text-danger";
            $icon = "far fa-square";
            $title = __('Enable user');
        }

        return "<a href='" . $url . "'
            title='{$title}'
            data-btn-disable='text-danger'
            data-icon-disable='far fa-square'
            data-title-disable='" . __('Enable ' . $title) . "'
            data-btn-enable='text-success'
            data-icon-enable='fas fa-check-square'
            data-title-enable='" . __('Disable ' . $title) . "'
            class='action-enable-disabled {$btn}'>
            <i class='{$icon}'></i>
        </a>";
    }
}

if (!function_exists('new_register')) {
    function new_register(string $linkRegister, string $title = 'New register', string $classLink = 'btn-add-by-link')
    {
        return "<a href='{$linkRegister}' class='btn btn-light " . $classLink . "'>" . __($title) . "</a>";
    }
}
