<?php
/**
 * Configurações gerais do sistema, carregadas a partir do .env
 */

define('ROOT_PATH', dirname(__DIR__));

// Autoload mínimo necessário antes do autoload completo (Env é usado muito cedo)
require_once ROOT_PATH . '/app/core/Env.php';

use App\Core\Env;

Env::load(ROOT_PATH . '/.env');

define('BASE_URL', rtrim(Env::get('APP_BASE_URL', 'http://localhost'), '/'));
define('ENVIRONMENT', Env::get('APP_ENV', 'production'));
// Opcional: ID do tenant padrão para deployments single-tenant (ex: hospedagem sem slug na URL)
define('DEFAULT_TENANT_ID', Env::get('DEFAULT_TENANT_ID', ''));

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