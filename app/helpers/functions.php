<?php
/** Funções auxiliares globais */

function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return BASE_URL . '/assets/' . ltrim($path, '/');
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function sanitize(?string $value): string
{
    return trim(strip_tags($value ?? ''));
}

function only_digits(?string $value): string
{
    return preg_replace('/\D+/', '', $value ?? '');
}

function old(string $key, string $default = ''): string
{
    return e($_SESSION['old'][$key] ?? $default);
}

function csrf_field(): string
{
    return \App\Core\Csrf::field();
}

/** Gera URLs já incluindo o slug do tenant atual automaticamente */
function tenant_url(string $path = ''): string
{
    $slug = \App\Core\Tenant::isLoaded() ? \App\Core\Tenant::slug() : '';
    $path = ltrim($path, '/');
    return BASE_URL . '/' . $slug . ($path ? '/' . $path : '');
}