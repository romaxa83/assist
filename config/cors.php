<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    // Все методы разрешены
    'allowed_methods' => ['*'],
    // Укажите доступный Origin для вашего фронтенда
//    'allowed_origins' => ['*'],
    'allowed_origins' => ['http://localhost:8080'],
    // Для wildcard Origins
    'allowed_origins_patterns' => [],
    // Все заголовки разрешены
    'allowed_headers' => ['*'],
    // Нет конкретных заголовков для ответа
    'exposed_headers' => [],
    // Не применяем ограничение "время жизни CORS"
    'max_age' => 0,
    // Если вы используете cookies, настройте этот параметр на true
    'supports_credentials' => false,

];
