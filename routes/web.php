<?php
/** @var \App\Core\Router $router */

// ---------- Rotas Públicas ----------
$router->get('/', 'HomeController@index');

$router->get('agendamento', 'AppointmentController@index');
$router->post('agendamento/horarios-disponiveis', 'AppointmentController@availableSlots');
$router->post('agendamento/salvar', 'AppointmentController@store');
$router->get('agendamento/sucesso', 'AppointmentController@success');

// ---------- Autenticação Admin ----------
$router->get('admin/login', 'Admin\AuthController@showLogin');
$router->post('admin/login', 'Admin\AuthController@login');
$router->get('admin/logout', 'Admin\AuthController@logout');

// ---------- Dashboard ----------
$router->get('admin/dashboard', 'Admin\DashboardController@index');

// ---------- Agenda ----------
$router->get('admin/agenda', 'Admin\AgendaController@index');
$router->post('admin/agenda/status', 'Admin\AgendaController@updateStatus');
$router->post('admin/agenda/excluir', 'Admin\AgendaController@delete');
$router->post('admin/agenda/bloquear-data', 'Admin\AgendaController@blockDate');
$router->post('admin/agenda/desbloquear-data', 'Admin\AgendaController@unblockDate');
$router->post('admin/agenda/bloquear-horario', 'Admin\AgendaController@blockHour');
// ---------- Clientes ----------
$router->get('admin/clientes', 'Admin\ClientController@index');
$router->get('admin/clientes/novo', 'Admin\ClientController@create');
$router->post('admin/clientes/salvar', 'Admin\ClientController@store');
$router->get('admin/clientes/{id}/editar', 'Admin\ClientController@edit');
$router->post('admin/clientes/{id}/atualizar', 'Admin\ClientController@update');
$router->get('admin/clientes/{id}', 'Admin\ClientController@show');
$router->post('admin/clientes/excluir', 'Admin\ClientController@destroy');

// ---------- Serviços ----------
$router->get('admin/servicos', 'Admin\ServiceController@index');
$router->get('admin/servicos/novo', 'Admin\ServiceController@create');
$router->post('admin/servicos/salvar', 'Admin\ServiceController@store');
$router->get('admin/servicos/{id}/editar', 'Admin\ServiceController@edit');
$router->post('admin/servicos/{id}/atualizar', 'Admin\ServiceController@update');
$router->post('admin/servicos/excluir', 'Admin\ServiceController@destroy');
$router->post('admin/servicos/status', 'Admin\ServiceController@toggleStatus');

// ---------- Configurações ----------
$router->get('admin/configuracoes', 'Admin\SettingController@index');
$router->post('admin/configuracoes/atualizar', 'Admin\SettingController@update');

// ---------- Relatórios ----------
$router->get('admin/relatorios', 'Admin\ReportController@index');
$router->get('admin/relatorios/exportar', 'Admin\ReportController@exportCsv');

// ---------- Depoimentos ----------
$router->get('admin/depoimentos', 'Admin\TestimonialController@index');
$router->get('admin/depoimentos/novo', 'Admin\TestimonialController@create');
$router->post('admin/depoimentos/salvar', 'Admin\TestimonialController@store');
$router->post('admin/depoimentos/excluir', 'Admin\TestimonialController@destroy');
$router->post('admin/depoimentos/status', 'Admin\TestimonialController@toggleStatus');

// ---------- Galeria ----------
$router->get('admin/galeria', 'Admin\GalleryController@index');
$router->get('admin/galeria/novo', 'Admin\GalleryController@create');
$router->post('admin/galeria/salvar', 'Admin\GalleryController@store');
$router->post('admin/galeria/excluir', 'Admin\GalleryController@destroy');

// ---------- Recuperação de Senha ----------
$router->get('admin/senha/esqueci', 'Admin\PasswordRecoveryController@showForgotForm');
$router->post('admin/senha/buscar', 'Admin\PasswordRecoveryController@findQuestion');
$router->get('admin/senha/pergunta', 'Admin\PasswordRecoveryController@showQuestionForm');
$router->post('admin/senha/verificar', 'Admin\PasswordRecoveryController@verifyAnswer');
$router->get('admin/senha/redefinir', 'Admin\PasswordRecoveryController@showResetForm');
$router->post('admin/senha/atualizar', 'Admin\PasswordRecoveryController@resetPassword');

// ---------- Super Admin (Impact) ----------
$router->get('superadmin/login', 'SuperAdmin\AuthController@showLogin');
$router->post('superadmin/login', 'SuperAdmin\AuthController@login');
$router->get('superadmin/logout', 'SuperAdmin\AuthController@logout');
$router->get('superadmin/tenants', 'SuperAdmin\TenantController@index');
$router->get('superadmin/tenants/novo', 'SuperAdmin\TenantController@create');
$router->post('superadmin/tenants/salvar', 'SuperAdmin\TenantController@store');
$router->post('superadmin/tenants/status', 'SuperAdmin\TenantController@toggleActive');