<?php

namespace App\Libraries;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrGenerator
{
    /**
     * Generate QR Code PNG binary menggunakan chillerlan/php-qrcode v5.
     */
    public function generatePngBinary(string $payload, int $scale = 10): string
    {
        $options = new QROptions([
            'outputType'   => QROutputInterface::GDIMAGE_PNG,
            'eccLevel'     => EccLevel::H,
            'scale'        => $scale,
            'outputBase64' => false,
        ]);

        return (string) (new QRCode($options))->render($payload);
    }

    /**
     * Generate dan simpan file QR Code PNG di bawah FCPATH.
     *
     * @return string Path lengkap file PNG yang tersimpan.
     */
    public function savePng(string $payload, string $relativeDir, string $fileBasename, int $scale = 10): string
    {
        $safeBase  = preg_replace('/[^a-zA-Z0-9_\-.]/', '_', basename($fileBasename)) ?: 'qr';
        $targetDir = rtrim(FCPATH . trim($relativeDir, '/'), DIRECTORY_SEPARATOR);

        if (! is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetFile = $targetDir . DIRECTORY_SEPARATOR . $safeBase . '.png';
        $pngBinary  = $this->generatePngBinary($payload, $scale);
        file_put_contents($targetFile, $pngBinary);

        return $targetFile;
    }
}

