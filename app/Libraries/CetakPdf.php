<?php

namespace App\Libraries;

use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

class CetakPdf
{
    /** @var string|array<int, float|int> */
    protected $paper = 'A4';

    protected string $orientation = 'portrait';

    public string $filename = 'laporan.pdf';

    /**
     * @param string|array<int, float|int> $paper
     */
    public function setPaper($paper, string $orientation = 'portrait'): self
    {
        $this->paper = $paper;
        // Normalisasi typo legacy CI3 ('potrait' -> 'portrait')
        $normalized = strtolower($orientation);
        $this->orientation = $normalized === 'potrait' ? 'portrait' : $normalized;

        return $this;
    }

    /**
     * Render view menjadi bytes PDF menggunakan Dompdf v3.
     *
     * @param array<string, mixed> $data
     */
    public function renderView(string $view, array $data = []): string
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper($this->paper, $this->orientation);

        $html = view($view, $data);
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $dompdf->output();
    }

    /**
     * Bangun response HTTP CI4 bertipe application/pdf (inline preview).
     *
     * @param array<string, mixed> $data
     */
    public function streamResponse(ResponseInterface $response, string $view, array $data = [], bool $download = false): ResponseInterface
    {
        $pdfBytes    = $this->renderView($view, $data);
        $disposition = $download ? 'attachment' : 'inline';
        $safeName    = preg_replace('/[^a-zA-Z0-9._\-\[\]]/', '_', $this->filename) ?: 'document.pdf';

        return $response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', $disposition . '; filename="' . $safeName . '"')
            ->setBody($pdfBytes);
    }

    /**
     * Helper aman membaca file aset lokal di public/ untuk disematkan sebagai data URI pada PDF.
     */
    public static function localImageDataUri(string $relativePath): string
    {
        $fullPath = FCPATH . ltrim($relativePath, '/');
        if (! is_file($fullPath)) {
            return '';
        }

        $bytes = file_get_contents($fullPath);
        if ($bytes === false) {
            return '';
        }

        $ext  = strtolower((string) pathinfo($fullPath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif'         => 'image/gif',
            'svg'         => 'image/svg+xml',
            default       => 'image/png',
        };

        return 'data:' . $mime . ';base64,' . base64_encode($bytes);
    }
}

