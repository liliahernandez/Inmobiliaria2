<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar las opciones de Cross-Origin Resource Sharing
    | para tu aplicación. Esto te permite especificar qué dominios pueden
    | acceder a tus recursos API y con qué métodos HTTP.
    |
    */

    // 👇 ¡IMPORTANTE! Añade 'login' y 'password/*' aquí
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'password/*'], 

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Recuerda cambiar '*' a tus dominios de frontend en producción.

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
