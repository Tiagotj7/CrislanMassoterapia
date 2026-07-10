<?php
namespace App\Models;

use App\Core\Model;

class BlockedDate extends Model
{
    public function block(string $date, ?string $reason = null): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO blocked_dates (blocked_date, reason) VALUES (:date, :reason)
             ON DUPLICATE KEY UPDATE reason = :reason"
        );
        $stmt->execute(['date' => $date, 'reason' => $reason]);
    }

    public function unblock(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM blocked_dates WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function blockRange(string $startDate, string $endDate, ?string $reason = null): void
    {
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        while ($current <= $end) {
            $this->block(date('Y-m-d', $current), $reason);
            $current = strtotime('+1 day', $current);
        }
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM blocked_dates ORDER BY blocked_date ASC");
        return $stmt->fetchAll();
    }

    public function blockHour(string $date, string $time, ?string $reason = null): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO blocked_hours (blocked_date, blocked_time, reason) 
             VALUES (:date, :time, :reason)
             ON DUPLICATE KEY UPDATE reason = :reason"
        );
        $stmt->execute(['date' => $date, 'time' => $time, 'reason' => $reason]);
    }

    public function unblockHour(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM blocked_hours WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}