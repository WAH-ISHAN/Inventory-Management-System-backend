<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'https://inventory-management-system-fronten-gilt.vercel.app',
        'https://inventory-management-system-frontend-4y8mmbzm8.vercel.app',
        env('FRONTEND_URL', 'http://localhost:3000')
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
