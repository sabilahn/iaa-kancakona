<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Session\Session;
use Psr\Log\LoggerInterface;

/**
 * BaseController menyediakan helper, session, dan renderer layout umum
 * untuk seluruh controller di aplikasi Kancakona.
 */
abstract class BaseController extends Controller
{
    /**
     * @var list<string>
     */
    protected $helpers = ['form', 'url'];

    protected Session $session;

    /**
     * @param CLIRequest|IncomingRequest $request
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = service('session');
    }

    /**
     * Render halaman standar dengan layout header + menu + konten + footer.
     *
     * @param array<string, mixed> $data
     */
    protected function renderPage(string $view, array $data = []): string
    {
        $data['title'] ??= 'IAA Investment';

        return view('layout/header', $data)
            . view('layout/menu', $data)
            . view($view, $data)
            . view('layout/footer', $data);
    }
}
