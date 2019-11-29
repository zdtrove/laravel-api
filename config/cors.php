<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowCredentials, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */

    'allowedOriginsWhitelist' => [
        'https://guild.tunglt.icd',
        'http://localhost:8080',
        'localhost'
    ],
    'allowedOrigins' => '*',
    'allowCredentials' => true,
    'allowedHeaders' => '*',
    'allowedMethods' => 'OPTIONS, GET, POST, PUT, PATCH, DELETE',
];
