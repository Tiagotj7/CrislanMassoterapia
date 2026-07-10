<?php
namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    /** Retorna todas as configurações como array associativo [key => value] */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM settings");
        $rows = $stmt->fetchAll();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }

    public function get(string $key, $default = null)
    {
        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = :key");
        $stmt->execute(['key' => $key]);
        $result = $stmt->fetch();
        return $result ? $result['setting_value'] : $default;
    }

    /** Atualiza várias configurações de uma vez (upsert) */
    public function updateMany(array $data): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO settings (setting_key, setting_value) VALUES (:key, :value)
             ON DUPLICATE KEY UPDATE setting_value = :value"
        );

        foreach ($data as $key => $value) {
            $stmt->execute(['key' => $key, 'value' => $value]);
        }
    }
}