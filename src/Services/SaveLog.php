<?php

namespace BRCas\Laravel\Services;

use BRCas\Laravel\Traits\Entities\Uuid;

class SaveLog
{
    use Uuid;

    private static $config;

    private static $data = [
        "uuid" => null,
    ];

    public static function logRequest(array $request, $url = null)
    {
        $id = self::getUuid();

        self::$data += [
            "uuid" => $id,
            "request" => $request,
            "date_initial" => time(),
        ];

        if ($url) {
            self::$data += [
                "url" => $url,
            ];
        }

        return $id;
    }

    public static function addCustomField($field, $value)
    {
        self::addKey("custom_" . $field, $value);
    }

    public static function addKey($field, $value)
    {
        $field = str_replace('.', '', $field);

        self::$data += [
            $field => $value,
        ];
        return self::class;
    }

    public static function logResponse(array $response)
    {
        self::$data += [
            "response" => $response,
        ];

        return self::class;
    }

    public static function save()
    {
        self::$data += [
            'project' => env('APP_NAME', __DIR__),
            'date_final' => time(),
        ];

        self::truncar(self::$data);

        return self::$data;
    }

    public static function truncar(&$data)
    {
        if (is_array($data)) {
            foreach ($data as $k => &$v) {
                if (is_array($v)) {
                    self::truncar($v);
                } else {
                    switch ($k) {
                        case 'token':
                        case 'secret':
                        case 'credential':
                            self::alterValue($data[$k]);
                            break;
                    }
                }
            }
        }
    }

    private static function alterValue(&$value)
    {
        if (is_array($value)) {
            foreach ($value as &$v) {
                self::alterValue($v);
            }
        } else {
            $value = substr($value, 0, 4) . str_repeat("x", strlen($value) - 8) . substr($value, -4);
        }
    }
}
