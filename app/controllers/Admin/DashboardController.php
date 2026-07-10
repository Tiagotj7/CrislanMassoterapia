<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\Appointment;
use App\Models\Client;

class DashboardController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $appointmentModel = new Appointment();
        $clientModel = new Client();

        $data = [
            'title'           => 'Dashboard | Painel Administrativo',
            'todayCount'      => $appointmentModel->countToday(),
            'todayAppointments' => $appointmentModel->getByDate(date('Y-m-d')),
            'nextAppointment' => $appointmentModel->getNext(),
            'monthlyStats'    => $appointmentModel->getMonthlyStats(),
            'weekAppointments' => $appointmentModel->getByRange(
                date('Y-m-d'),
                date('Y-m-d', strtotime('+6 days'))
            ),
        ];

        $this->view('admin/dashboard/index', $data);
    }
}