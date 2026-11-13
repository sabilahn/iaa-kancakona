<?php

namespace App\Controllers;

use App\Libraries\CetakPdf;
use App\Models\LaporanModel;
use CodeIgniter\HTTP\ResponseInterface;

class Laporan extends BaseController
{
    private const NAMA_BULAN = [
        '1'  => 'Januari',
        '01' => 'Januari',
        '2'  => 'Februari',
        '02' => 'Februari',
        '3'  => 'Maret',
        '03' => 'Maret',
        '4'  => 'April',
        '04' => 'April',
        '5'  => 'Mei',
        '05' => 'Mei',
        '6'  => 'Juni',
        '06' => 'Juni',
        '7'  => 'Juli',
        '07' => 'Juli',
        '8'  => 'Agustus',
        '08' => 'Agustus',
        '9'  => 'September',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ];

    protected LaporanModel $laporanModel;
    protected CetakPdf $cetakPdf;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->cetakPdf     = new CetakPdf();
    }

    public function index(): void
    {
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function renderCetak(string $bodyView, array $data): string
    {
        return view('laporan/cetak-wrapper', array_merge($data, [
            'body_view' => 'laporan/' . $bodyView,
        ]));
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function exportPdf(string $filename, string $bodyView, array $data): ResponseInterface
    {
        ini_set('memory_limit', '512M');

        $this->cetakPdf->setPaper('A4', 'landscape');
        $this->cetakPdf->filename = $filename;

        return $this->cetakPdf->streamResponse(
            $this->response,
            'laporan/pdf-wrapper',
            array_merge($data, ['body_view' => 'laporan/' . $bodyView])
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function exportXls(string $filename, string $bodyView, array $data): ResponseInterface
    {
        $safeName = preg_replace('/[^a-zA-Z0-9._\-]/', '_', $filename) ?: 'laporan.xls';

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'application/vnd-ms-excel')
            ->setHeader('Content-Disposition', 'attachment; filename=' . $safeName)
            ->setBody(view('laporan/xls-wrapper', array_merge($data, [
                'body_view' => 'laporan/' . $bodyView,
            ])));
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function getBagiHasilFiltered(string $tahun, string $kecamatan): array
    {
        $conds = ['tahun' => $tahun];
        if ($kecamatan !== 'Semua' && $kecamatan !== '') {
            $conds['kec'] = $kecamatan;
        }

        return $this->laporanModel->getDataBagiHasil($conds);
    }

    // PROFIL PEMEGANG SAHAM
    public function cetakProfilPemegangSaham(): string
    {
        return $this->renderCetak('v-profil-pemegang-saham', [
            'title'    => 'Profil Pemegang Saham',
            'profiles' => $this->laporanModel->getUsers(),
            'xls_url'  => site_url('laporan/konfersi_profil_pemegang_saham'),
            'pdf_url'  => site_url('laporan/export_profil_pemegang_saham'),
        ]);
    }

    public function konfersiProfilPemegangSaham(): ResponseInterface
    {
        return $this->exportXls('profil_pemegang_saham.xls', 'v-profil-pemegang-saham', [
            'profiles' => $this->laporanModel->getUsers(),
        ]);
    }

    public function exportProfilPemegangSaham(): ResponseInterface
    {
        return $this->exportPdf('profil-pemegang-saham.pdf', 'v-profil-pemegang-saham', [
            'profiles' => $this->laporanModel->getUsers(),
            'title'    => 'Profil Pemegang Saham',
        ]);
    }

    // SALDO AWAL TAHUN
    public function saldoAwalTahun(): string
    {
        return $this->renderPage('laporan/saldo-awal-tahun', [
            'title' => 'IAA Investment | Laporan',
        ]);
    }

    public function cetakSaldoAwalTahun(): string
    {
        $tahun = (string) ($this->request->getPost('tahun') ?: date('Y'));

        return $this->renderCetak('v-saldo-awal-tahun', [
            'title'           => 'Saldo Awal Tahun',
            'data_saldo_awal' => $this->laporanModel->getSaldoAwal($tahun),
            'tahun'           => $tahun,
            'xls_url'         => site_url('laporan/konfersi_saldo_awal_tahun/' . rawurlencode($tahun)),
            'pdf_url'         => site_url('laporan/export_saldo_awal_tahun/' . rawurlencode($tahun)),
        ]);
    }

    public function konfersiSaldoAwalTahun(string $tahun): ResponseInterface
    {
        return $this->exportXls('saldo_awal_tahun_' . $tahun . '.xls', 'v-saldo-awal-tahun', [
            'tahun'           => $tahun,
            'data_saldo_awal' => $this->laporanModel->getSaldoAwal($tahun),
        ]);
    }

    public function exportSaldoAwalTahun(string $tahun): ResponseInterface
    {
        return $this->exportPdf('saldo_awal_tahun.pdf', 'v-saldo-awal-tahun', [
            'tahun'           => $tahun,
            'data_saldo_awal' => $this->laporanModel->getSaldoAwal($tahun),
            'title'           => 'Saldo Awal Tahun',
        ]);
    }

    // TRANSAKSI TERKINI
    public function cetakTransaksiTerkini(): string
    {
        $tglAwal   = date('Y-01-02');
        $tglAkhir  = date('Y-m-d');
        $namaBulan = self::NAMA_BULAN[date('m')] ?? '';

        return $this->renderCetak('v-transaksi-investasi-terkini', [
            'title'              => 'Transaksi Investasi Terkini',
            'nama_bulan'         => $namaBulan,
            'data_trans_terkini' => $this->laporanModel->getDataTransaksiTerkini($tglAwal, $tglAkhir),
            'xls_url'            => site_url('laporan/konfersi_transaksi_terkini/' . rawurlencode($namaBulan) . '/' . $tglAwal . '/' . $tglAkhir),
            'pdf_url'            => site_url('laporan/export_transaksi_terkini/' . rawurlencode($namaBulan) . '/' . $tglAwal . '/' . $tglAkhir),
        ]);
    }

    public function konfersiTransaksiTerkini(string $namaBulan, string $tglAwal, string $tglAkhir): ResponseInterface
    {
        return $this->exportXls('transaksi_investasi_sd_' . date('d-m-Y') . '.xls', 'v-transaksi-investasi-terkini', [
            'nama_bulan'         => urldecode($namaBulan),
            'data_trans_terkini' => $this->laporanModel->getDataTransaksiTerkini($tglAwal, $tglAkhir),
        ]);
    }

    public function exportTransaksiTerkini(string $namaBulan, string $tglAwal, string $tglAkhir): ResponseInterface
    {
        return $this->exportPdf('transaksi_terkini.pdf', 'v-transaksi-investasi-terkini', [
            'nama_bulan'         => urldecode($namaBulan),
            'data_trans_terkini' => $this->laporanModel->getDataTransaksiTerkini($tglAwal, $tglAkhir),
            'title'              => 'Transaksi Terkini',
        ]);
    }

    // TRANSAKSI BULANAN
    public function transaksiBulanan(): string
    {
        return $this->renderPage('laporan/transaksi-investasi-bln', [
            'title' => 'IAA Investment | Laporan',
        ]);
    }

    public function cetakTransaksiInvestBln(): string
    {
        $bulan     = (string) ($this->request->getPost('bulanTransaksi') ?: date('n'));
        $tahun     = (string) ($this->request->getPost('tahunTransaksi') ?: date('Y'));
        $namaBulan = self::NAMA_BULAN[$bulan] ?? 'Januari';

        return $this->renderCetak('v-transaksi-investasi-bln', [
            'title'          => 'Transaksi Investasi Bulanan',
            'nama_bulan'     => $namaBulan,
            'tahun'          => $tahun,
            'data_trans_bln' => $this->laporanModel->getDataTransaksiBln($bulan, $tahun),
            'xls_url'        => site_url('laporan/konfersi_transaksi_invest_bln/' . rawurlencode($namaBulan) . '/' . rawurlencode($bulan) . '/' . rawurlencode($tahun)),
            'pdf_url'        => site_url('laporan/export_transaksi_invest_bln/' . rawurlencode($namaBulan) . '/' . rawurlencode($bulan) . '/' . rawurlencode($tahun)),
        ]);
    }

    public function konfersiTransaksiInvestBln(string $namaBulan, string $bulan, string $tahun): ResponseInterface
    {
        $decodedNama = urldecode($namaBulan);

        return $this->exportXls('transaksi_investasi_' . $decodedNama . '_' . $tahun . '.xls', 'v-transaksi-investasi-bln', [
            'bulan'          => $bulan,
            'nama_bulan'     => $decodedNama,
            'tahun'          => $tahun,
            'data_trans_bln' => $this->laporanModel->getDataTransaksiBln($bulan, $tahun),
        ]);
    }

    public function exportTransaksiInvestBln(string $namaBulan, string $bulan, string $tahun): ResponseInterface
    {
        $decodedNama = urldecode($namaBulan);

        return $this->exportPdf('transaksi_bulan_' . $decodedNama . '.pdf', 'v-transaksi-investasi-bln', [
            'bulan'          => $bulan,
            'nama_bulan'     => $decodedNama,
            'tahun'          => $tahun,
            'data_trans_bln' => $this->laporanModel->getDataTransaksiBln($bulan, $tahun),
            'title'          => 'Transaksi Bulan ' . $decodedNama,
        ]);
    }

    // PENARIKAN INVESTASI
    public function penarikanInvestasi(): string
    {
        return $this->renderPage('laporan/penarikan-investasi', [
            'title' => 'IAA Investment | Laporan',
        ]);
    }

    public function cetakPenarikanInvestasi(): string
    {
        $tahun = (string) ($this->request->getPost('tahun') ?: date('Y'));

        return $this->renderCetak('v-penarikan-investasi', [
            'title'          => 'Penarikan Investasi',
            'tahun'          => $tahun,
            'data_penarikan' => $this->laporanModel->getData('v_penarikan_investasi', ['tahun' => $tahun]),
            'xls_url'        => site_url('laporan/konfersi_penarikan_investasi/' . rawurlencode($tahun)),
            'pdf_url'        => site_url('laporan/export_penarikan_investasi/' . rawurlencode($tahun)),
        ]);
    }

    public function konfersiPenarikanInvestasi(string $tahun): ResponseInterface
    {
        return $this->exportXls('penarikan_investasi_' . $tahun . '.xls', 'v-penarikan-investasi', [
            'tahun'          => $tahun,
            'data_penarikan' => $this->laporanModel->getData('v_penarikan_investasi', ['tahun' => $tahun]),
        ]);
    }

    public function exportPenarikanInvestasi(string $tahun): ResponseInterface
    {
        return $this->exportPdf('penarikan_investasi_' . $tahun . '.pdf', 'v-penarikan-investasi', [
            'tahun'          => $tahun,
            'data_penarikan' => $this->laporanModel->getData('v_penarikan_investasi', ['tahun' => $tahun]),
            'title'          => 'Penarikan Investasi',
        ]);
    }

    // SALDO HARIAN
    public function saldoHarian(): string
    {
        return $this->renderPage('laporan/saldo-harian', [
            'title' => 'IAA Investment | Laporan',
        ]);
    }

    public function cetakSaldoHarian(): string
    {
        $tahun = (string) ($this->request->getPost('tahunSaldo') ?: date('Y'));

        return $this->renderCetak('v-saldo-harian', [
            'title'      => 'Saldo Harian',
            'tahun'      => $tahun,
            'data_saldo' => $this->laporanModel->getData('v_saldo_harian', ['tahun' => $tahun]),
            'xls_url'    => site_url('laporan/konfersi_saldo_harian/' . rawurlencode($tahun)),
            'pdf_url'    => site_url('laporan/export_saldo_harian/' . rawurlencode($tahun)),
        ]);
    }

    public function konfersiSaldoHarian(string $tahun): ResponseInterface
    {
        return $this->exportXls('saldo_harian_' . $tahun . '.xls', 'v-saldo-harian', [
            'tahun'      => $tahun,
            'data_saldo' => $this->laporanModel->getData('v_saldo_harian', ['tahun' => $tahun]),
        ]);
    }

    public function exportSaldoHarian(string $tahun): ResponseInterface
    {
        return $this->exportPdf('saldo_harian_' . $tahun . '.pdf', 'v-saldo-harian', [
            'tahun'      => $tahun,
            'data_saldo' => $this->laporanModel->getData('v_saldo_harian', ['tahun' => $tahun]),
            'title'      => 'Saldo Harian',
        ]);
    }

    // DISTRIBUSI DAN PORSI
    public function distribusiDanPorsi(): string
    {
        return $this->renderPage('laporan/distribusi-dan-porsi', [
            'title' => 'IAA Investment | Laporan',
        ]);
    }

    public function cetakDistribusiDanPorsi(): string
    {
        $tahun = (string) ($this->request->getPost('tahunDisporsi') ?: date('Y'));

        return $this->renderCetak('v-distribusi-dan-porsi', [
            'title'       => 'Distribusi dan Porsi',
            'tahun'       => $tahun,
            'data_dispen' => $this->laporanModel->getData('porsi_dispen', ['tahun' => $tahun]),
            'data_disda'  => $this->laporanModel->getData('porsi_disda', ['tahun' => $tahun]),
            'xls_url'     => site_url('laporan/konfersi_distribusi_dan_porsi/' . rawurlencode($tahun)),
            'pdf_url'     => site_url('laporan/export_distribusi_dan_porsi/' . rawurlencode($tahun)),
        ]);
    }

    public function konfersiDistribusiDanPorsi(string $tahun): ResponseInterface
    {
        return $this->exportXls('distribusi_dan_porsi_' . $tahun . '.xls', 'v-distribusi-dan-porsi', [
            'tahun'       => $tahun,
            'data_dispen' => $this->laporanModel->getData('porsi_dispen', ['tahun' => $tahun]),
            'data_disda'  => $this->laporanModel->getData('porsi_disda', ['tahun' => $tahun]),
        ]);
    }

    public function exportDistribusiDanPorsi(string $tahun): ResponseInterface
    {
        return $this->exportPdf('distribusi_dan_porsi_' . $tahun . '.pdf', 'v-distribusi-dan-porsi', [
            'tahun'       => $tahun,
            'data_dispen' => $this->laporanModel->getData('porsi_dispen', ['tahun' => $tahun]),
            'data_disda'  => $this->laporanModel->getData('porsi_disda', ['tahun' => $tahun]),
            'title'       => 'Distribusi dan Porsi',
        ]);
    }

    // BAGI HASIL SHU
    public function bagiHasilShu(): string
    {
        return $this->renderPage('laporan/bagi-hasil-shu', [
            'title'     => 'IAA Investment | Laporan',
            'kecamatan' => $this->laporanModel->getKec(),
        ]);
    }

    public function cetakBagiHasilShu(): string
    {
        $tahun = (string) ($this->request->getPost('tahunBagiHasil') ?: date('Y'));
        $kec   = (string) ($this->request->getPost('Kec') ?: 'Semua');

        return $this->renderCetak('v-bagi-hasil-shu', [
            'title'           => 'Bagi Hasil SHU',
            'tahun'           => $tahun,
            'kecamatan'       => $kec,
            'data_bagi_hasil' => $this->getBagiHasilFiltered($tahun, $kec),
            'xls_url'         => site_url('laporan/konfersi_bagi_hasil_shu/' . rawurlencode($tahun) . '/' . rawurlencode($kec)),
            'pdf_url'         => site_url('laporan/export_bagi_hasil_shu/' . rawurlencode($tahun) . '/' . rawurlencode($kec)),
        ]);
    }

    public function konfersiBagiHasilShu(string $tahun, string $kec): ResponseInterface
    {
        $decodedKec = urldecode($kec);

        return $this->exportXls('bagi_hasil_shu_' . $decodedKec . '_' . $tahun . '.xls', 'v-bagi-hasil-shu', [
            'tahun'           => $tahun,
            'kecamatan'       => $decodedKec,
            'data_bagi_hasil' => $this->getBagiHasilFiltered($tahun, $decodedKec),
        ]);
    }

    public function exportBagiHasilShu(string $tahun, string $kec): ResponseInterface
    {
        $decodedKec = urldecode($kec);

        return $this->exportPdf('bagi_hasil_shu_' . $tahun . '.pdf', 'v-bagi-hasil-shu', [
            'tahun'           => $tahun,
            'kecamatan'       => $decodedKec,
            'data_bagi_hasil' => $this->getBagiHasilFiltered($tahun, $decodedKec),
            'title'           => 'Bagi Hasil SHU',
            'hide_bank'       => true,
        ]);
    }

    // LABEL SURAT & REKENING BANK
    public function labelSurat(): string
    {
        return $this->renderPage('laporan/label-surat', [
            'title'     => 'IAA Investment | Label',
            'kecamatan' => $this->laporanModel->getKec(),
        ]);
    }

    public function cetakLabelSurat(): ResponseInterface
    {
        $tahun = (string) ($this->request->getPost('tahun') ?: date('Y'));
        $kec   = (string) ($this->request->getPost('Kec') ?: '');

        $this->cetakPdf->setPaper([0, 0, 869, 415]);
        $this->cetakPdf->filename = 'label_surat_' . $kec . '_' . $tahun . '.pdf';

        return $this->cetakPdf->streamResponse($this->response, 'laporan/pdf-label-surat', [
            'title'      => 'Label',
            'data_label' => $this->laporanModel->getData('v_label_surat', ['tahun' => $tahun, 'kec' => $kec]),
        ]);
    }

    public function rekBankAnggota(): string
    {
        return $this->renderCetak('v-rek-bank-anggota', [
            'title'    => 'IAA Investment | Rekening Bank Anggota',
            'data_rek' => $this->laporanModel->getRekeningBank(),
            'xls_url'  => site_url('laporan/konfersi_rek_bank_anggota'),
        ]);
    }

    public function konfersiRekBankAnggota(): ResponseInterface
    {
        return $this->exportXls('rek_bank_anggota.xls', 'v-rek-bank-anggota', [
            'data_rek' => $this->laporanModel->getRekeningBank(),
        ]);
    }
}
