<?php

use App\Controllers\Api;
use App\Libraries\CetakPdf;
use App\Libraries\QrGenerator;
use App\Models\ApiModel;
use App\Models\TransaksiInvestasiModel;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class MigratedModulesTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        session()->destroy();
    }

    public function testQrGeneratorProducesValidPng(): void
    {
        $qr  = new QrGenerator();
        $png = $qr->generatePngBinary('http://kancakona.test/surat_saham/index/TEST001');

        $this->assertStringStartsWith("\x89PNG\r\n\x1a\n", $png);
        $this->assertGreaterThan(500, strlen($png));
    }

    public function testCetakPdfRendersValidPdfBytes(): void
    {
        $pdf = new CetakPdf();
        $pdf->setPaper([0, 0, 869, 415], 'potrait');

        $bytes = $pdf->renderView('laporan/pdf-label-surat', [
            'title'      => 'Label Test',
            'data_label' => [[
                'no_rekening'       => '0001-0001',
                'nama'              => 'anggota uji',
                'dsn'               => 'Dsn. Tengah',
                'ds'                => 'Desa A',
                'rt'                => '01',
                'rw'                => '02',
                'kec'               => 'Guluk-Guluk',
                'kab'               => 'Sumenep',
                'nisbah_bagi_hasil' => 150000,
            ]],
        ]);

        $this->assertStringStartsWith('%PDF-', $bytes);
    }

    public function testTransaksiInvestasiUpdateSaldoUserAccumulatesCorrectly(): void
    {
        $model = new class () extends TransaksiInvestasiModel {
            public function __construct()
            {
            }

            public function cekTransaksiTahun(string $noRekening, string $tahun): array
            {
                return [
                    ['tgl_transaksi' => '2026-01-01', 'jml_transaksi' => 1000],
                    ['tgl_transaksi' => '2026-01-03', 'jml_transaksi' => 500],
                ];
            }

            public function cekPenarikanTahun(string $noRekening, string $tahun): array
            {
                return [
                    ['tgl_penarikan' => '2026-01-04', 'jml_penarikan' => 200],
                ];
            }
        };

        // Hari 1 (01-01): +1000 => akumulasi 1000, saldo 1000
        // Hari 2 (01-02): 0     => akumulasi 1000, saldo 2000
        // Hari 3 (01-03): +500  => akumulasi 1500, saldo 3500
        // Hari 4 (01-04): -200  => akumulasi 1300, saldo 4800
        $saldo = $model->updateSaldoUser('0001-0001', '2026-01-04', '2026');
        $this->assertEqualsWithDelta(4800.0, $saldo, 0.001);
    }

    public function testTransaksiInvestasiGetHariTransaksiNeverDividesByZero(): void
    {
        $model = new class () extends TransaksiInvestasiModel {
            public function __construct()
            {
            }
        };

        [$harian, $tahunan] = $model->getHariTransaksi('2026-01-01', '2026-01-01');
        $this->assertEqualsWithDelta(1.0, $harian, 0.001);
        $this->assertGreaterThanOrEqual(1.0, $tahunan);
    }

    public function testApiUsersControllerDoesNotExposeUserpass(): void
    {
        $fakeApiModel = new class () extends ApiModel {
            public function __construct()
            {
            }

            public function getUsers(): array
            {
                return [[
                    'user_id'  => 'adm',
                    'nama'     => 'administrator',
                    'username' => 'admin',
                    'userpass' => 'SHOULD_NOT_APPEAR',
                    'role'     => 'super_user',
                ]];
            }
        };

        $controller = new class ($fakeApiModel) extends Api {
            public function __construct(ApiModel $model)
            {
                $this->apiModel = $model;
            }
        };

        $controller->initController(
            \Config\Services::request(),
            service('response'),
            service('logger')
        );

        $response = $controller->users();
        $this->assertSame(200, $response->getStatusCode());

        $payload = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($payload);
        $this->assertSame('Administrator', $payload[0]['name']);
        $this->assertArrayNotHasKey('userpass', $payload[0]);
    }

    public function testApiModelGetUsersSqlSelectsSafeColumnsOnly(): void
    {
        $source = file_get_contents(APPPATH . 'Models/ApiModel.php');
        $this->assertIsString($source);
        $this->assertStringContainsString("->select('user_id, nama, username, role')", $source);
        $this->assertStringNotContainsString('userpass', $source);
    }
}
