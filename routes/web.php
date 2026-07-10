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