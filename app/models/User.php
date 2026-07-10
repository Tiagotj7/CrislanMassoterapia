<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email AND active = 1 LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function verifyLoginAttempts(string $email): int
    {
        // Simplificado: usa tabela logs para contar tentativas falhas nos últimos 15 min
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM logs 
             WHERE action = 'login_failed' 
             AND description = :email 
             AND created_at > (NOW() - INTERVAL 15 MINUTE)"
        );
        $stmt->execute(['email' => $email]);
        return (int) $stmt->fetch()['total'];
    }

    public function registerFailedAttempt(string $email, string $ip): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO logs (action, description, ip_address) VALUES ('login_failed', :email, :ip)"
        );
        $stmt->execute(['email' => $email, 'ip' => $ip]);
    }

    public function registerLogin(int $userId, string $ip): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO logs (user_id, action, description, ip_address) VALUES (:id, 'login_success', 'Login realizado', :ip)"
        );
        $stmt->execute(['id' => $userId, 'ip' => $ip]);
    }
}