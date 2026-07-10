<?php
/**
 * Front Controller — todo o tráfego passa por aqui via .htaccess.
 */

require __DIR__ . '/config/config.php';
require __DIR__ . '/app/helpers/functions.php';

// Autoload manual (sem Composer, compatível com InfinityFree)
spl_autoload_register(function (string $class) {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $parts = explode('\\', $relative);
    $parts[0] = strtolower($parts[0]); // Core -> core, Controllers -> controllers...
    $file = ROOT_PATH . '/app/' . implode('/', $parts) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\App;
use App\Core\Session;

Session::start();

$app = new App();
$app->run();