<?php

namespace App\Controllers;

use App\Models\DistribusiDanPorsiModel;

class DistribusiDanPorsi extends BaseController
{
    protected DistribusiDanPorsiModel $distribusiModel;

    public function __construct()
    {
        $this->distribusiModel = new DistribusiDanPorsiModel();
    }

    public function index(): string
    {
        $tahun = (string) $this->request->getPost('tahunDispor');
        if ($tahun === '') {
            $tahun = date('Y');
        }

        return $this->renderPage('distribusi_dan_porsi', [
            'title'        => 'IAA Investment | Distribusi dan Porsi',
            'tahun'        => $tahun,
            'porsi_dispen' => $this->distribusiModel->showByTahun('porsi_dispen', $tahun),
            'porsi_disda'  => $this->distribusiModel->showByTahun('porsi_disda', $tahun),
        ]);
    }
}

