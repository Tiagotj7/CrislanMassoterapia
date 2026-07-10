<?php
namespace App\Models;

use App\Core\Model;

class SuperAdmin extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM super_admins WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();
        return $admin ?: null;
    }
}