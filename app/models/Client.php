<?php
namespace App\Models;

use App\Core\Model;

class Client extends Model
{
    public function findByPhoneOrCreate(string $name, string $phone): int
    {
        $stmt = $this->db->prepare("SELECT id FROM clients WHERE phone = :phone LIMIT 1");
        $stmt->execute(['phone' => $phone]);
        $client = $stmt->fetch();

        if ($client) {
            return (int) $client['id'];
        }

        $stmt = $this->db->prepare("INSERT INTO clients (name, phone) VALUES (:name, :phone)");
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

    /** Lista paginada com busca por nome ou telefone */
    public function paginate(int $page = 1, int $perPage = 15, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = '';

        if ($search !== '') {
            $where = "WHERE name LIKE :search OR phone LIKE :search";
            $params['search'] = "%{$search}%";
        }

        $stmt = $this->db->prepare(
            "SELECT * FROM clients {$where} ORDER BY name ASC LIMIT :limit OFFSET :offset"
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindValue(':limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function countAll(string $search = ''): int
    {
        $params = [];
        $where = '';

        if ($search !== '') {
            $where = "WHERE name LIKE :search OR phone LIKE :search";
            $params['search'] = "%{$search}%";
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM clients {$where}");
        $stmt->execute($params);

        return (int) $stmt->fetch()['total'];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch();
        return $client ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO clients (name, phone, email, notes) VALUES (:name, :phone, :email, :notes)"
        );
        $stmt->execute([
            'name'  => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?: null,
            'notes' => $data['notes'] ?: null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $stmt = $this->db->prepare(
            "UPDATE clients SET name = :name, phone = :phone, email = :email, notes = :notes WHERE id = :id"
        );
        $stmt->execute([
            'name'  => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?: null,
            'notes' => $data['notes'] ?: null,
            'id'    => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    /** Histórico de agendamentos de um cliente específico */
    public function getAppointmentHistory(int $clientId): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, s.name AS service_name, s.price
             FROM appointments a
             JOIN services s ON s.id = a.service_id
             WHERE a.client_id = :id
             ORDER BY a.appointment_date DESC, a.appointment_time DESC"
        );
        $stmt->execute(['id' => $clientId]);
        return $stmt->fetchAll();
    }

    public function existsPhone(string $phone, ?int $excludeId = null): bool
    {
        $sql = "SELECT id FROM clients WHERE phone = :phone";
        $params = ['phone' => $phone];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }
}