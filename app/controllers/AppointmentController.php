<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;

class AppointmentController extends Controller
{
    public function index(): void
    {
        $serviceModel = new Service();

        $this->view('appointment/index', [
            'title'    => 'Agendar Horário | Crislan Massoterapeuta',
            'services' => $serviceModel->getAllActive(),
        ]);
    }

    /** Endpoint AJAX: retorna horários livres para data + serviço */
    public function availableSlots(): void
    {
        $date = $_POST['date'] ?? '';
        $serviceId = (int) ($_POST['service_id'] ?? 0);

        if (!$this->isValidDate($date) || $serviceId <= 0) {
            $this->json(['success' => false, 'message' => 'Dados inválidos.'], 422);
        }

        $serviceModel = new Service();
        $service = $serviceModel->find($serviceId);

        if (!$service) {
            $this->json(['success' => false, 'message' => 'Serviço não encontrado.'], 404);
        }

        $appointmentModel = new Appointment();
        $slots = $appointmentModel->getAvailableSlots($date, (int) $service['duration_minutes']);

        $this->json(['success' => true, 'slots' => $slots]);
    }

    /** Salva o agendamento (com validação server-side completa) */
    public function store(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Sessão expirada. Recarregue a página.'], 419);
        }

        $name      = sanitize($_POST['name'] ?? '');
        $phone     = only_digits($_POST['phone'] ?? '');
        $notes     = sanitize($_POST['notes'] ?? '');
        $date      = $_POST['date'] ?? '';
        $time      = $_POST['time'] ?? '';
        $serviceId = (int) ($_POST['service_id'] ?? 0);

        $errors = $this->validate($name, $phone, $date, $time, $serviceId);

        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => implode(' ', $errors)], 422);
        }

        $appointmentModel = new Appointment();

        // Revalida disponibilidade no servidor (evita condição de corrida)
        if (!$appointmentModel->isSlotAvailable($date, $time . ':00')) {
            $this->json(['success' => false, 'message' => 'Este horário acabou de ser reservado. Escolha outro.'], 409);
        }

        $clientModel = new Client();
        $clientId = $clientModel->findByPhoneOrCreate($name, $phone);

        $appointmentId = $appointmentModel->create([
            'client_id'  => $clientId,
            'service_id' => $serviceId,
            'date'       => $date,
            'time'       => $time . ':00',
            'notes'      => $notes,
        ]);

        $clientModel->incrementAppointmentCount($clientId);

        Session::set('last_appointment_id', $appointmentId);

        $this->json([
            'success'  => true,
            'message'  => 'Agendamento realizado com sucesso!',
            'redirect' => url('agendamento/sucesso'),
        ]);
    }

    public function success(): void
    {
        $this->view('appointment/success', ['title' => 'Agendamento Confirmado']);
    }

    private function isValidDate(string $date): bool
    {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date && $date >= date('Y-m-d');
    }

    private function validate(string $name, string $phone, string $date, string $time, int $serviceId): array
    {
        $errors = [];

        if (mb_strlen($name) < 3) {
            $errors[] = 'Informe seu nome completo.';
        }
        if (mb_strlen($phone) < 10) {
            $errors[] = 'Informe um telefone válido com DDD.';
        }
        if (!$this->isValidDate($date)) {
            $errors[] = 'Data inválida.';
        }
        if (!preg_match('/^\d{2}:\d{2}$/', $time)) {
            $errors[] = 'Horário inválido.';
        }
        if ($serviceId <= 0) {
            $errors[] = 'Selecione um serviço.';
        }

        return $errors;
    }
}