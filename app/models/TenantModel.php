<?php
namespace App\Models;

use App\Core\Model;

class TenantModel extends Model
{
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tenants WHERE slug = :slug AND active = 1");
        $stmt->execute(['slug' => $slug]);
        $tenant = $stmt->fetch();
        return $tenant ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM tenants WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $tenant = $stmt->fetch();
        return $tenant ?: null;
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM tenants ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function slugExists(string $slug): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM tenants WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return (bool) $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            "INSERT INTO tenants (slug, name, business_type, plan, trial_ends_at) 
             VALUES (:slug, :name, :type, :plan, :trial)"
        );
        $stmt->execute([
            'slug'  => $data['slug'],
            'name'  => $data['name'],
            'type'  => $data['business_type'],
            'plan'  => $data['plan'] ?? 'trial',
            'trial' => $data['trial_ends_at'] ?? date('Y-m-d', strtotime('+14 days')),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function toggleActive(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE tenants SET active = NOT active WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    /** Cria as configurações padrão para um novo tenant baseado no tipo de negócio */
    public function seedDefaultSettings(int $tenantId, string $businessType): void
    {
        $defaults = require ROOT_PATH . '/config/business_types.php';
        $texts = $defaults[$businessType] ?? $defaults['outro'];

        $stmt = $this->db->prepare(
            "INSERT INTO settings (tenant_id, setting_key, setting_value) VALUES (:tid, :key, :value)"
        );

        $settings = [
            'site_name'             => $texts['default_site_name'],
            'whatsapp'              => '',
            'instagram'             => '',
            'address'               => '',
            'google_maps_embed'     => '',
            'opening_time'          => '08:00',
            'closing_time'          => '19:00',
            'lunch_start'           => '12:00',
            'lunch_end'             => '13:30',
            'works_sunday'          => '0',
            'slot_interval_minutes' => '60',
            'auto_message'          => $texts['default_auto_message'],
        ];

        foreach ($settings as $key => $value) {
            $stmt->execute(['tid' => $tenantId, 'key' => $key, 'value' => $value]);
        }
    }
}