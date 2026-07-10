<?php
/**
 * Credenciais do banco MySQL, lidas do arquivo .env
 */

use App\Core\Env;

return [
    'host'    => Env::get('DB_HOST'),
    'dbname'  => Env::get('DB_NAME'),
    'user'    => Env::get('DB_USER'),
    'pass'    => Env::get('DB_PASS'),
    'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
];