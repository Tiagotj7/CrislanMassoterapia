<?php
namespace App\Models;

use App\Core\Model;

class Client extends Model
{
    /** Busca cliente pelo telefone ou cria um novo (evita duplicidade) */
    public function findByPhoneOrCreate(string $name, string $phone): int
    {
        $stmt = $this->db->prepare("SELECT id FROM clients WHERE phone = :phone LIMIT 1");
        $stmt->execute(['phone' => $phone]);
        $client = $stmt->fetch();

        if ($client) {
            return (int) $client['id'];
        }

        $stmt = $this->db->prepare(
            "INSERT INTO clients (name, phone) VALUES (:name, :phone)"
        );
        $stmt->execute(['name' => $name, 'phone' => $phone]);

        return (int) $this->db->lastInsertId();
    }

    public function incrementAppointmentCount(int $clientId): void
    {
        $stmt = $this->db->prepare(
            "UPDATE clients SET total_appointments = total_appointments + 1 WHERE id = :id"
        );
        $stmt->execute(['id' => $clientId]);
    }
}