<?php
namespace App\Core;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected bool $tenantScoped = true; // desative em models globais (Tenant, SuperAdmin)

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /** Retorna o tenant_id atual, lançando erro se não estiver carregado */
    protected function tenantId(): int
    {
        return Tenant::id();
    }
}