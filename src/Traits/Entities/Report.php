<?php


namespace BRCas\Laravel\Traits\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;

trait Report
{
    public static function bootReport()
    {
        static::deleted(function ($obj) {
            $data = self::getData($obj);
            $action = "deleted";
            if (in_array(SoftDeletes::class, class_uses($obj))) {
                $action = "updated";
            }
            $data += ['model_action' => $action];
            self::send($data, $obj->getTable());
        });

        static::created(function ($obj) {
            $data = self::getData($obj) + ["model_action" => "created"];
            self::send($data, $obj->getTable());
        });

        static::updated(function ($obj) {
            $data = self::getData($obj) + ["model_action" => "updated"];
            self::send($data, $obj->getTable());
        });
    }

    public static function send(array $data, $table = null)
    {
        $data += [
            'project' => env('APP_NAME'),
            'model_table' => $table,
        ];

        if (empty($data['model_action'])) {
            throw new \Exception("Don't exist key model_action");
        }

        ksort($data);
    }

    private static function getData($obj): array
    {
        $dados = $obj->toArray();
        $fillable = (array)$obj->getFillable();

        $n = [];

        if (method_exists($obj, 'getFieldReport')) {
            foreach ($obj->getFieldReport() as $rs) {
                $n += [$rs => $obj->$rs];
            }
        }

        $keyName = $obj->getKeyName();

        $n += [
            'id' => $obj->$keyName,
        ];

        foreach ($dados as $k => $rs) {
            if (in_array($k, $fillable)) {
                $n += [$k => $rs];
            }
        }
        if ($obj->timestamps == true) {
            $n += [
                'created_at' => $obj->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $obj->updated_at->format('Y-m-d H:i:s'),
            ];
        }

        if (in_array(SoftDeletes::class, class_uses($obj))) {
            $n += [
                'deleted_at' => $obj->deleted_at ? $obj->deleted_at->format('Y-m-d H:i:s') : null,
            ];
        }
        return $n;
    }
}
