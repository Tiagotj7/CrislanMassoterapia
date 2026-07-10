<?php
namespace App\Models;

use App\Core\Model;

class Appointment extends Model
{
    /** Gera horários disponíveis para uma data e serviço específicos */
    public function getAvailableSlots(string $date, int $durationMinutes): array
    {
        $settings = $this->getSettings();

        // Domingo bloqueado, se configurado
        $dayOfWeek = (int) date('w', strtotime($date));
        if ($dayOfWeek === 0 && $settings['works_sunday'] === '0') {
            return [];
        }

        // Data bloqueada manualmente pelo admin
        $stmt = $this->db->prepare("SELECT 1 FROM blocked_dates WHERE blocked_date = :date");
        $stmt->execute(['date' => $date]);
        if ($stmt->fetch()) {
            return [];
        }

        $slots = [];
        $interval = (int) $settings['slot_interval_minutes'];
        $start = strtotime("{$date} {$settings['opening_time']}");
        $end   = strtotime("{$date} {$settings['closing_time']}");
        $lunchStart = strtotime("{$date} {$settings['lunch_start']}");
        $lunchEnd   = strtotime("{$date} {$settings['lunch_end']}");

        // Horários já ocupados nesta data
        $stmt = $this->db->prepare(
            "SELECT appointment_time FROM appointments 
             WHERE appointment_date = :date AND status != 'cancelado'"
        );
        $stmt->execute(['date' => $date]);
        $taken = array_column($stmt->fetchAll(), 'appointment_time');

        // Horários bloqueados manualmente
        $stmt = $this->db->prepare(
            "SELECT blocked_time FROM blocked_hours WHERE blocked_date = :date"
        );
        $stmt->execute(['date' => $date]);
        $blockedHours = array_column($stmt->fetchAll(), 'blocked_time');

        for ($time = $start; ($time + $durationMinutes * 60) <= $end; $time += $interval * 60) {
            $timeStr = date('H:i:s', $time);

            // Pula horário de almoço
            if ($time >= $lunchStart && $time < $lunchEnd) {
                continue;
            }

            if (in_array($timeStr, $taken, true) || in_array($timeStr, $blockedHours, true)) {
                continue;
            }

            // Não permitir agendar em horário já passado (se for hoje)
            if ($date === date('Y-m-d') && $time <= time()) {
                continue;
            }

            $slots[] = date('H:i', $time);
        }

        return $slots;
    }

    public function isSlotAvailable(string $date, string $time): bool
    {
        $stmt = $this->db->prepare(
            "SELECT 1 FROM appointments 
             WHERE appointment_date = :date AND appointment_time = :time AND status != 'cancelado'"
        );
        $stmt->execute(['date' => $date, 'time' => $time]);
        return !$stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO appointments (client_id, service_id, appointment_date, appointment_time, notes)
             VALUES (:client_id, :service_id, :date, :time, :notes)"
        );
        $stmt->execute([
            'client_id'  => $data['client_id'],
            'service_id' => $data['service_id'],
            'date'       => $data['date'],
            'time'       => $data['time'],
            'notes'      => $data['notes'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    private function getSettings(): array
    {
        $stmt = $this->db->query("SELECT setting_key, setting_value FROM settings");
        $rows = $stmt->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        return $settings;
    }
}

public function getByDate(string $date): array
{
    $stmt = $this->db->prepare(
        "SELECT a.*, c.name AS client_name, c.phone AS client_phone, 
                s.name AS service_name, s.duration_minutes
         FROM appointments a
         JOIN clients c ON c.id = a.client_id
         JOIN services s ON s.id = a.service_id
         WHERE a.appointment_date = :date
         ORDER BY a.appointment_time ASC"
    );
    $stmt->execute(['date' => $date]);
    return $stmt->fetchAll();
}

/** Agendamentos de um intervalo (usado na visão semanal/mensal) */
public function getByRange(string $start, string $end): array
{
    $stmt = $this->db->prepare(
        "SELECT a.*, c.name AS client_name, s.name AS service_name
         FROM appointments a
         JOIN clients c ON c.id = a.client_id
         JOIN services s ON s.id = a.service_id
         WHERE a.appointment_date BETWEEN :start AND :end
         ORDER BY a.appointment_date ASC, a.appointment_time ASC"
    );
    $stmt->execute(['start' => $start, 'end' => $end]);
    return $stmt->fetchAll();
}

public function countToday(): int
{
    $stmt = $this->db->prepare(
        "SELECT COUNT(*) as total FROM appointments 
         WHERE appointment_date = CURDATE() AND status != 'cancelado'"
    );
    $stmt->execute();
    return (int) $stmt->fetch()['total'];
}

public function getNext(): ?array
{
    $stmt = $this->db->prepare(
        "SELECT a.*, c.name AS client_name, s.name AS service_name
         FROM appointments a
         JOIN clients c ON c.id = a.client_id
         JOIN services s ON s.id = a.service_id
         WHERE (a.appointment_date > CURDATE() 
                OR (a.appointment_date = CURDATE() AND a.appointment_time >= CURTIME()))
           AND a.status != 'cancelado'
         ORDER BY a.appointment_date ASC, a.appointment_time ASC
         LIMIT 1"
    );
    $stmt->execute();
    $result = $stmt->fetch();
    return $result ?: null;
}

public function getMonthlyStats(): array
{
    $stmt = $this->db->query(
        "SELECT DATE_FORMAT(appointment_date, '%Y-%m') as month, COUNT(*) as total
         FROM appointments
         WHERE status != 'cancelado'
         AND appointment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
         GROUP BY month
         ORDER BY month ASC"
    );
    return $stmt->fetchAll();
}

public function updateStatus(int $id, string $status): void
{
    $allowed = ['pendente', 'confirmado', 'concluido', 'cancelado'];
    if (!in_array($status, $allowed, true)) {
        throw new \InvalidArgumentException('Status inválido.');
    }

    $stmt = $this->db->prepare("UPDATE appointments SET status = :status WHERE id = :id");
    $stmt->execute(['status' => $status, 'id' => $id]);
}

public function delete(int $id): void
{
    $stmt = $this->db->prepare("DELETE FROM appointments WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

public function find(int $id): ?array
{
    $stmt = $this->db->prepare(
        "SELECT a.*, c.name AS client_name, c.phone AS client_phone, s.name as service_name
         FROM appointments a
         JOIN clients c ON c.id = a.client_id
         JOIN services s ON s.id = a.service_id
         WHERE a.id = :id"
    );
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch();
    return $result ?: null;
}

