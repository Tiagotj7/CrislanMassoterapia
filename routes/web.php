<?php
/** @var \App\Core\Router $router */

// ---------- Rotas Públicas ----------
$router->get('/', 'HomeController@index');

$router->get('agendamento', 'AppointmentController@index');
$router->post('agendamento/horarios-disponiveis', 'AppointmentController@availableSlots');
$router->post('agendamento/salvar', 'AppointmentController@store');
$router->get('agendamento/sucesso', 'AppointmentController@success');

// ---------- Área Administrativa (Fase 2) ----------
$router->get('admin/login', 'Admin\AuthController@showLogin');
$router->post('admin/login', 'Admin\AuthController@login');
$router->get('admin/logout', 'Admin\AuthController@logout');
$router->get('admin/dashboard', 'Admin\DashboardController@index');