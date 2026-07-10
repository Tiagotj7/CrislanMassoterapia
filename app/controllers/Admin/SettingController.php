<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Helpers\ImageHelper;
use App\Middleware\AuthMiddleware;
use App\Models\Setting;

class SettingController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::handle();
    }

    public function index(): void
    {
        $settingModel = new Setting();

        $this->view('admin/settings/index', [
            'title'    => 'Configurações | Painel Administrativo',
            'settings' => $settingModel->getAll(),
        ]);
    }

    public function update(): void
    {
        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            Session::flash('error', 'Sessão expirada. Tente novamente.');
            $this->redirect('admin/configuracoes');
        }

        $settingModel = new Setting();

        $data = [
            'whatsapp'              => only_digits($_POST['whatsapp'] ?? ''),
            'instagram'             => filter_var(trim($_POST['instagram'] ?? ''), FILTER_SANITIZE_URL),
            'address'               => sanitize($_POST['address'] ?? ''),
            'google_maps_embed'     => trim($_POST['google_maps_embed'] ?? ''), // já vem escapado no template
            'opening_time'          => $_POST['opening_time'] ?? '08:00',
            'closing_time'          => $_POST['closing_time'] ?? '19:00',
            'lunch_start'           => $_POST['lunch_start'] ?? '12:00',
            'lunch_end'             => $_POST['lunch_end'] ?? '13:30',
            'works_sunday'          => isset($_POST['works_sunday']) ? '1' : '0',
            'slot_interval_minutes' => (int) ($_POST['slot_interval_minutes'] ?? 60),
            'auto_message'          => sanitize($_POST['auto_message'] ?? ''),
        ];

        // Upload de logo (opcional)
        if (!empty($_FILES['logo']['name'])) {
            try {
                $oldLogo = $settingModel->get('logo');
                $data['logo'] = ImageHelper::upload($_FILES['logo'], 'branding', 400);
                if ($oldLogo) {
                    ImageHelper::delete($oldLogo);
                }
            } catch (\RuntimeException $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect('admin/configuracoes');
            }
        }

        // Upload de foto de perfil (opcional)
        if (!empty($_FILES['photo']['name'])) {
            try {
                $oldPhoto = $settingModel->get('photo');
                $data['photo'] = ImageHelper::upload($_FILES['photo'], 'branding', 800);
                if ($oldPhoto) {
                    ImageHelper::delete($oldPhoto);
                }
            } catch (\RuntimeException $e) {
                Session::flash('error', $e->getMessage());
                $this->redirect('admin/configuracoes');
            }
        }

        $settingModel->updateMany($data);

        Session::flash('success', 'Configurações atualizadas com sucesso.');
        $this->redirect('admin/configuracoes');
    }
}