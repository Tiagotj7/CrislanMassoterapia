<?php
use App\Core\Session;
$currentPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
?>
<nav class="admin-sidebar d-flex flex-column p-3">
    <div class="text-center mb-4">
        <img src="<?= asset('img/logo-white.png') ?>" alt="Crislan" style="max-width: 120px;">
    </div>

    <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item">
            <a class="nav-link <?= str_contains($currentPath, 'dashboard') ? 'active' : '' ?>" href="<?= url('admin/dashboard') ?>">
                <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= str_contains($currentPath, 'agenda') ? 'active' : '' ?>" href="<?= url('admin/agenda') ?>">
                <i class="fa-solid fa-calendar-days me-2"></i> Agenda
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= str_contains($currentPath, 'clientes') ? 'active' : '' ?>" href="<?= url('admin/clientes') ?>">
                <i class="fa-solid fa-users me-2"></i> Clientes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= str_contains($currentPath, 'servicos') ? 'active' : '' ?>" href="<?= url('admin/servicos') ?>">
                <i class="fa-solid fa-spa me-2"></i> Serviços
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= str_contains($currentPath, 'configuracoes') ? 'active' : '' ?>" href="<?= url('admin/configuracoes') ?>">
                <i class="fa-solid fa-gear me-2"></i> Configurações
            </a>
        </li>
    </ul>

    <div class="mt-auto">
        <hr class="text-white-50">
        <p class="text-white-50 small mb-1">Olá, <?= e(Session::get('admin_name')) ?></p>
        <a href="<?= url('admin/logout') ?>" class="btn btn-sm btn-outline-light w-100">
            <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
        </a>
    </div>
</nav>

<li class="nav-item">
    <a class="nav-link <?= str_contains($currentPath, 'depoimentos') ? 'active' : '' ?>" href="<?= url('admin/depoimentos') ?>">
        <i class="fa-solid fa-star me-2"></i> Depoimentos
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= str_contains($currentPath, 'galeria') ? 'active' : '' ?>" href="<?= url('admin/galeria') ?>">
        <i class="fa-solid fa-images me-2"></i> Galeria
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?= str_contains($currentPath, 'relatorios') ? 'active' : '' ?>" href="<?= url('admin/relatorios') ?>">
        <i class="fa-solid fa-chart-simple me-2"></i> Relatórios
    </a>
</li>