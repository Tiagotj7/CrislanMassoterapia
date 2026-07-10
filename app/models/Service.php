<?php
namespace App\Models;

use App\Core\Model;

class Service extends Model
{
    public function getAllActive(): array
    {
        $stmt = $this->db->query("SELECT * FROM services WHERE active = 1 ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = :id AND active = 1");
        $stmt->execute(['id' => $id]);
        $service = $stmt->fetch();
        return $service ?: null;
    }
}