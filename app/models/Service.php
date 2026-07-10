<?php
namespace App\Models;

use App\Core\Model;

class Service extends Model
{
    public function getAllActive(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM services WHERE tenant_id = :tid AND active = 1 ORDER BY name ASC"
        );
        $stmt->execute(['tid' => $this->tenantId()]);
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE tenant_id = :tid ORDER BY name ASC");
        $stmt->execute(['tid' => $this->tenantId()]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = :id AND tenant_id = :tid");
        $stmt->execute(['id' => $id, 'tid' => $this->tenantId()]);
        $service = $stmt->fetch();
        return $service ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO services (tenant_id, name, description, duration_minutes, price, image, active)
             VALUES (:tid, :name, :description, :duration, :price, :image, :active)"
        );
        $stmt->execute([
            'tid'         => $this->tenantId(),
            'name'        => $data['name'],
            'description' => $data['description'] ?: null,
            'duration'    => $data['duration_minutes'],
            'price'       => $data['price'],
            'image'       => $data['image'] ?? null,
            'active'      => $data['active'] ?? 1,
        ]);

        return (int) $this->db->lastInsertId();
    }

    // update(), delete(), toggleStatus(), hasAppointments() seguem o MESMO padrão:
    // sempre adicionar "AND tenant_id = :tid" no WHERE e passar $this->tenantId() nos parâmetros.
}