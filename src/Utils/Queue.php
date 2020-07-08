<?php


namespace BRCas\Utils;

use Illuminate\Support\Facades\Log;

class Queue
{
    public static function publish($routeName, $queue, $message, $data = [])
    {
        try {
            \Bschmitt\Amqp\Facades\Amqp::publish($routeName, $message, ['queue' => $queue] + $data);
        } catch (\Exception $e) {
            Log::error("Impossible connect rabbitmq - " . $e->getMessage());
        }
    }

    public static function consume($queue, $funcao)
    {
        \Bschmitt\Amqp\Facades\Amqp::consume($queue, function ($message, $resolver) use ($funcao) {
            try {
                $retorno = $funcao($message->body, $message->delivery_info['routing_key'], $resolver);
                if ($retorno !== false) {
                    $resolver->acknowledge($message);
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        });
    }
}
