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

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM services ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM services WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $service = $stmt->fetch();
        return $service ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO services (name, description, duration_minutes, price, image, active)
             VALUES (:name, :description, :duration, :price, :image, :active)"
        );
        $stmt->execute([
            'name'        => $data['name'],
            'description' => $data['description'] ?: null,
            'duration'    => $data['duration_minutes'],
            'price'       => $data['price'],
            'image'       => $data['image'] ?? null,
            'active'      => $data['active'] ?? 1,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sql = "UPDATE services SET name = :name, description = :description,
                duration_minutes = :duration, price = :price, active = :active";
        $params = [
            'name'        => $data['name'],
            'description' => $data['description'] ?: null,
            'duration'    => $data['duration_minutes'],
            'price'       => $data['price'],
            'active'      => $data['active'] ?? 1,
            'id'          => $id,
        ];

        if (!empty($data['image'])) {
            $sql .= ", image = :image";
            $params['image'] = $data['image'];
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM services WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function toggleStatus(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE services SET active = NOT active WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function hasAppointments(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM appointments WHERE service_id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetch();
    }
}