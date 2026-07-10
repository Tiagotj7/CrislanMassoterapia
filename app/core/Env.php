<?php
namespace App\Core;

/**
 * Carregador de variáveis de ambiente a partir de um arquivo .env.
 * Implementação própria, sem Composer — compatível com InfinityFree.
 */
class Env
{
    private static bool $loaded = false;
    private static array $variables = [];

    public static function load(string $path): void
    {
        if (self::$loaded) {
            return;
        }

        if (!file_exists($path)) {
            throw new \RuntimeException(
                "Arquivo .env não encontrado em: {$path}. Copie o .env.example e preencha os valores."
            );
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignora comentários e linhas vazias
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            // Remove aspas simples ou duplas envolvendo o valor
            $value = trim($value, "\"'");

            self::$variables[$key] = $value;

            // Disponibiliza também via getenv()/$_ENV para compatibilidade
            if (!array_key_exists($key, $_ENV)) {
                $_ENV[$key] = $value;
                putenv("{$key}={$value}");
            }
        }

        self::$loaded = true;
    }

    public static function get(string $key, $default = null)
    {
        return self::$variables[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return array_key_exists($key, self::$variables);
    }
}