<?php
namespace App\Core;

class Session
{
    private const TIMEOUT = 1800; // 30 minutos de inatividade

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        self::checkTimeout();
    }

    private static function checkTimeout(): void
    {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > self::TIMEOUT) {
            $_SESSION = [];
            session_destroy();
            session_start();
        }
        $_SESSION['last_activity'] = time();
    }

    public static function set(string $key, $value): void { $_SESSION[$key] = $value; }
    public static function get(string $key, $default = null) { return $_SESSION[$key] ?? $default; }
    public static function has(string $key): bool { return isset($_SESSION[$key]); }
    public static function remove(string $key): void { unset($_SESSION[$key]); }

    public static function flash(string $key, ?string $message = null)
    {
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
            return null;
        }
        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }
}