<?php

namespace App\Controllers;

use App\Models\BagiHasilModel;

class BagiHasil extends BaseController
{
    protected BagiHasilModel $bagiHasilModel;

    public function __construct()
    {
        $this->bagiHasilModel = new BagiHasilModel();
    }

    public function index(): string
    {
        $tahun = (string) $this->request->getPost('tahunBagiHasil');
        if ($tahun === '') {
            $tahun = date('Y');
        }

        return $this->renderPage('bagi_hasil', [
            'title'           => 'IAA Investment | Bagi Hasil SHU',
            'tahun'           => $tahun,
            'data_bagi_hasil' => $this->bagiHasilModel->getByTahun($tahun),
        ]);
    }

    public function lihatData(): string
    {
        return $this->index();
    }
}

