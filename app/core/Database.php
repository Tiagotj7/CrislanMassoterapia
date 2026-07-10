<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Conexão única (Singleton) com o banco via PDO.
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require ROOT_PATH . '/config/database.php';

            try {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                self::$instance = new PDO($dsn, $config['user'], $config['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                error_log('Erro de conexão com o banco: ' . $e->getMessage());
                http_response_code(500);
                die('Erro ao conectar ao banco de dados. Tente novamente mais tarde.');
            }
        }

        return self::$instance;
    }
}