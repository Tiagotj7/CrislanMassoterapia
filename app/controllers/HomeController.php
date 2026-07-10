<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Service;

class HomeController extends Controller
{
    public function index(): void
    {
        $serviceModel = new Service();
        $services = $serviceModel->getAllActive();

        $this->view('home/index', [
            'title'    => 'Crislan Massoterapeuta | Massoterapia Esportiva',
            'services' => $services,
        ]);
    }
}