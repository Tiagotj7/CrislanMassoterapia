<?php
namespace App\Core;

/**
 * Guarda o tenant atualmente identificado pela URL.
 * Funciona como um "Registry" acessado estaticamente durante toda a requisição.
 */
class Tenant
{
    private static ?array $current = null;

    public static function set(array $tenant): void
    {
        self::$current = $tenant;
    }

    public static function id(): int
    {
        if (self::$current === null) {
            throw new \RuntimeException('Tenant não identificado. Verifique o TenantMiddleware.');
        }
        return (int) self::$current['id'];
    }

    public static function slug(): string
    {
        return self::$current['slug'] ?? '';
    }

    public static function data(): array
    {
        return self::$current ?? [];
    }

    public static function businessType(): string
    {
        return self::$current['business_type'] ?? 'outro';
    }

    public static function isLoaded(): bool
    {
        return self::$current !== null;
    }
}