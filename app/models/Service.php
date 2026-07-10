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

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id, 'tid' => $this->tenantId()];

        foreach ($data as $k => $v) {
            $fields[] = "{$k} = :{$k}";
            $params[$k] = $v;
        }

        if (empty($fields)) {
            return false;
        }

        $sql = 'UPDATE services SET ' . implode(', ', $fields) . ' WHERE id = :id AND tenant_id = :tid';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM services WHERE id = :id AND tenant_id = :tid');
        return $stmt->execute(['id' => $id, 'tid' => $this->tenantId()]);
    }

    public function toggleStatus(int $id): bool
    {
        // Busca status atual
        $stmt = $this->db->prepare('SELECT active FROM services WHERE id = :id AND tenant_id = :tid');
        $stmt->execute(['id' => $id, 'tid' => $this->tenantId()]);
        $row = $stmt->fetch();
        if (!$row) return false;
        $new = $row['active'] ? 0 : 1;
        $stmt = $this->db->prepare('UPDATE services SET active = :active WHERE id = :id AND tenant_id = :tid');
        return $stmt->execute(['active' => $new, 'id' => $id, 'tid' => $this->tenantId()]);
    }

    public function hasAppointments(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as c FROM appointments WHERE service_id = :id AND tenant_id = :tid');
        $stmt->execute(['id' => $id, 'tid' => $this->tenantId()]);
        $row = $stmt->fetch();
        return ($row && $row['c'] > 0);
    }
}