<?php
namespace App\Models;

use App\Core\Model;

class Testimonial extends Model
{
    public function getAllActive(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM testimonials WHERE active = 1 ORDER BY created_at DESC LIMIT 10"
        );
        return $stmt->fetchAll();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM testimonials ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO testimonials (client_name, comment, rating, active) 
             VALUES (:name, :comment, :rating, :active)"
        );
        $stmt->execute([
            'name'    => $data['client_name'],
            'comment' => $data['comment'],
            'rating'  => $data['rating'] ?? 5,
            'active'  => $data['active'] ?? 1,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM testimonials WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function toggleStatus(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE testimonials SET active = NOT active WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}