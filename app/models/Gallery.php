<?php
namespace App\Models;

use App\Core\Model;

class Gallery extends Model
{
    public function getAllActive(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM gallery WHERE active = 1 ORDER BY order_position ASC, id DESC"
        );
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO gallery (title, image, order_position, active) 
             VALUES (:title, :image, :position, :active)"
        );
        $stmt->execute([
            'title'    => $data['title'] ?? null,
            'image'    => $data['image'],
            'position' => $data['order_position'] ?? 0,
            'active'   => $data['active'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("SELECT image FROM gallery WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();

        $stmt = $this->db->prepare("DELETE FROM gallery WHERE id = :id");
        $stmt->execute(['id' => $id]);

        if ($item) {
            \App\Helpers\ImageHelper::delete($item['image']);
        }
    }
}