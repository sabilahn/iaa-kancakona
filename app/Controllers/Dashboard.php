<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        return $this->renderPage('dashboard', [
            'title' => 'IAA Investment | Dashboard',
        ]);
    }
}

