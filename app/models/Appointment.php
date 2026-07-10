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