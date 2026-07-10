<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\Gallery;

class HomeController extends Controller
{
    public function index(): void
    {
        $serviceModel = new Service();
        $settingModel = new Setting();
        $testimonialModel = new Testimonial();
        $galleryModel = new Gallery();

        $settings = $settingModel->getAll();

        $this->view('home/index', [
            'title'        => ($settings['site_name'] ?? 'Crislan Massoterapeuta') . ' | Massoterapia Esportiva Profissional',
            'description'  => 'Agende sua sessão de massoterapia esportiva com Crislan. Atendimento especializado para atletas, recuperação muscular e bem-estar. Agendamento 100% online.',
            'services'     => $serviceModel->getAllActive(),
            'settings'     => $settings,
            'testimonials' => $testimonialModel->getAllActive(),
            'gallery'      => $galleryModel->getAllActive(),
        ]);
    }
}