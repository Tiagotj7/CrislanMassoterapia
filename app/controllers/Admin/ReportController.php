<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Core\Database;

class ReportController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate = $_GET['end'] ?? date('Y-m-t');

        $db = Database::getInstance();

        // Resumo geral do período
        $stmt = $db->prepare(
            "SELECT 
                COUNT(*) as total_appointments,
                SUM(CASE WHEN status = 'concluido' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'cancelado' THEN 1 ELSE 0 END) as cancelled,
                COALESCE(SUM(CASE WHEN a.status = 'concluido' THEN s.price ELSE 0 END), 0) as total_revenue
             FROM appointments a
             JOIN services s ON s.id = a.service_id
             WHERE a.appointment_date BETWEEN :start AND :end"
        );
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $summary = $stmt->fetch();

        // Serviços mais procurados
        $stmt = $db->prepare(
            "SELECT s.name, COUNT(*) as total
             FROM appointments a
             JOIN services s ON s.id = a.service_id
             WHERE a.appointment_date BETWEEN :start AND :end
             AND a.status != 'cancelado'
             GROUP BY s.id
             ORDER BY total DESC
             LIMIT 5"
        );
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $topServices = $stmt->fetchAll();

        // Clientes mais frequentes
        $stmt = $db->prepare(
            "SELECT c.name, c.phone, COUNT(*) as total
             FROM appointments a
             JOIN clients c ON c.id = a.client_id
             WHERE a.appointment_date BETWEEN :start AND :end
             AND a.status != 'cancelado'
             GROUP BY c.id
             ORDER BY total DESC
             LIMIT 5"
        );
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $topClients = $stmt->fetchAll();

        $this->view('admin/reports/index', [
            'title'       => 'Relatórios | Painel Administrativo',
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'summary'     => $summary,
            'topServices' => $topServices,
            'topClients'  => $topClients,
        ]);
    }

    /** Exporta agendamentos do período em CSV */
    public function exportCsv(): void
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate = $_GET['end'] ?? date('Y-m-t');

        $db = Database::getInstance();
        $stmt = $db->prepare(
            "SELECT a.appointment_date, a.appointment_time, c.name AS client_name, 
                    c.phone, s.name AS service_name, s.price, a.status
             FROM appointments a
             JOIN clients c ON c.id = a.client_id
             JOIN services s ON s.id = a.service_id
             WHERE a.appointment_date BETWEEN :start AND :end
             ORDER BY a.appointment_date ASC, a.appointment_time ASC"
        );
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $rows = $stmt->fetchAll();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relatorio_' . $startDate . '_' . $endDate . '.csv"');

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF"); // BOM para acentuação correta no Excel

        fputcsv($output, ['Data', 'Hora', 'Cliente', 'Telefone', 'Serviço', 'Preço', 'Status'], ';');

        foreach ($rows as $row) {
            fputcsv($output, [
                date('d/m/Y', strtotime($row['appointment_date'])),
                date('H:i', strtotime($row['appointment_time'])),
                $row['client_name'],
                $row['phone'],
                $row['service_name'],
                number_format($row['price'], 2, ',', '.'),
                ucfirst($row['status']),
            ], ';');
        }

        fclose($output);
        exit;
    }
}