<?php


namespace BRCas\Laravel\Utils;


class Message
{
    public static function created() {
        return __('Registro cadastrado com sucesso');
    }

    public static function updated() {
        return __('Registro atualizado com sucesso');
    }
}
