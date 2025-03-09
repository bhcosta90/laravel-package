<?php

declare(strict_types = 1);

return [
    'enable'  => env('CRYPT_ENABLE', false),
    'default' => 'main',
    'main'    => [
        'salt'     => env('HASHIDS_SALT', env('APP_KEY')),
        'length'   => env('HASHIDS_LENGTH', 10),
        'alphabet' => env('HASHIDS_ALPHABET', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'),
    ],
];
