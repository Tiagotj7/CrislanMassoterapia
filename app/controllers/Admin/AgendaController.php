<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Middleware\AuthMiddleware;
use App\Models\Appointment;
use App\Models\BlockedDate;

class AgendaController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $view = $_GET['view'] ?? 'day'; // day | week | month
        $date = $_GET['date'] ?? date('Y-m-d');

        $appointmentModel = new Appointment();

        switch ($view) {
            case 'week':
                $start = date('Y-m-d', strtotime('monday this week', strtotime($date)));
                $end   = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
                $appointments = $appointmentModel->getByRange($start, $end);
                break;

            case 'month':
                $start = date('Y-m-01', strtotime($date));
                $end   = date('Y-m-t', strtotime($date));
                $appointments = $appointmentModel->getByRange($start, $end);
                break;

            default: // day
                $appointments = $appointmentModel->getByDate($date);
                $start = $end = $date;
        }

        $blockedModel = new BlockedDate();

        $this->view('admin/agenda/index', [
            'title'        => 'Agenda | Painel Administrativo',
            'view'         => $view,
            'date'         => $date,
            'appointments' => $appointments,
            'blockedDates' => $blockedModel->getAll(),
        ]);
    }

    /** Atualiza status via AJAX (confirmar, cancelar, concluir) */
    public function updateStatus(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        try {
            $appointmentModel = new Appointment();
            $appointmentModel->updateStatus($id, $status);
            $this->json(['success' => true, 'message' => 'Status atualizado.']);
        } catch (\InvalidArgumentException $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function delete(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $appointmentModel = new Appointment();
        $appointmentModel->delete($id);

        $this->json(['success' => true, 'message' => 'Agendamento excluído.']);
    }

    /** Bloqueia uma data inteira ou um intervalo (férias) */
    public function blockDate(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? $startDate;
        $reason = sanitize($_POST['reason'] ?? '');

        $blockedModel = new BlockedDate();
        $blockedModel->blockRange($startDate, $endDate, $reason);

        $this->json(['success' => true, 'message' => 'Período bloqueado com sucesso.']);
    }

    public function unblockDate(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $id = (int) ($_POST['id'] ?? 0);
        $blockedModel = new BlockedDate();
        $blockedModel->unblock($id);

        $this->json(['success' => true, 'message' => 'Bloqueio removido.']);
    }

    /** Bloqueia um horário específico dentro de uma data */
    public function blockHour(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->json(['success' => false, 'message' => 'Token inválido.'], 419);
        }

        $date = $_POST['date'] ?? '';
        $time = $_POST['time'] ?? '';
        $reason = sanitize($_POST['reason'] ?? '');

        $blockedModel = new BlockedDate();
        $blockedModel->blockHour($date, $time . ':00', $reason);

        $this->json(['success' => true, 'message' => 'Horário bloqueado.']);
    }
}