<?php
/**
 * Configurações gerais do sistema.
 * Ajustar BASE_URL conforme o domínio final na InfinityFree.
 */

define('ROOT_PATH', dirname(__DIR__));
define('BASE_URL', 'https://seudominio.infinityfreeapp.com'); // sem barra no final
define('ENVIRONMENT', 'production'); // 'development' durante testes

if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', ROOT_PATH . '/database/logs/error.log');
}

date_default_timezone_set('America/Sao_Paulo');
mb_internal_encoding('UTF-8');