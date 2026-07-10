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

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function getSecurityQuestion(string $email): ?string
    {
        $stmt = $this->db->prepare("SELECT security_question FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result['security_question'] ?? null;
    }

    public function verifySecurityAnswer(string $email, string $answer): ?int
    {
        $stmt = $this->db->prepare(
            "SELECT id, security_answer_hash FROM users WHERE email = :email"
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !$user['security_answer_hash']) {
            return null;
        }

        $normalizedAnswer = mb_strtolower(trim($answer));

        return password_verify($normalizedAnswer, $user['security_answer_hash'])
            ? (int) $user['id']
            : null;
    }

    public function updatePassword(int $userId, string $newPassword): void
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->execute(['password' => $hash, 'id' => $userId]);
    }

    public function setSecurityQuestion(int $userId, string $question, string $answer): void
    {
        $answerHash = password_hash(mb_strtolower(trim($answer)), PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "UPDATE users SET security_question = :question, security_answer_hash = :answer WHERE id = :id"
        );
        $stmt->execute(['question' => $question, 'answer' => $answerHash, 'id' => $userId]);
    }

    /** Proteção contra força bruta na pergunta de segurança */
    public function countRecoveryAttempts(string $email): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) as total FROM logs 
         WHERE action = 'recovery_failed' AND description = :email 
         AND created_at > (NOW() - INTERVAL 15 MINUTE)"
        );
        $stmt->execute(['email' => $email]);
        return (int) $stmt->fetch()['total'];
    }

    public function registerFailedRecovery(string $email, string $ip): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO logs (action, description, ip_address) VALUES ('recovery_failed', :email, :ip)"
        );
        $stmt->execute(['email' => $email, 'ip' => $ip]);
    }
}
