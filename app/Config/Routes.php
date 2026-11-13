<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Publik / Autentikasi
$routes->get('/', 'Auth::index');
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Terautentikasi
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {
    $routes->get('auth/ubah_password', 'Auth::ubahPassword');
    $routes->post('auth/update_password', 'Auth::updatePassword');
    $routes->get('dashboard', 'Dashboard::index');

    // Profil Pemegang Saham
    $routes->get('profil_pemegang_saham', 'ProfilPemegangSaham::index');
    $routes->get('profil_pemegang_saham/show', 'ProfilPemegangSaham::show');
    $routes->get('profil_pemegang_saham/get_bank', 'ProfilPemegangSaham::getBank');
    $routes->post('profil_pemegang_saham/get_bank', 'ProfilPemegangSaham::getBank');
    $routes->get('profil_pemegang_saham/tambah_data_anggota', 'ProfilPemegangSaham::tambahDataAnggota');
    $routes->post('profil_pemegang_saham/tambah_data', 'ProfilPemegangSaham::tambahData');
    $routes->get('profil_pemegang_saham/ubah_data_anggota/(:segment)', 'ProfilPemegangSaham::ubahDataAnggota/$1');
    $routes->post('profil_pemegang_saham/ubah_data', 'ProfilPemegangSaham::ubahData');
    $routes->get('profil_pemegang_saham/lihat_data_anggota/(:segment)', 'ProfilPemegangSaham::lihatDataAnggota/$1');
    $routes->post('profil_pemegang_saham/hapus_anggota', 'ProfilPemegangSaham::hapusAnggota');

    // Transaksi Investasi & Cetak Surat
    $routes->get('transaksi_investasi', 'TransaksiInvestasi::index');
    $routes->post('transaksi_investasi', 'TransaksiInvestasi::index');
    $routes->get('transaksi_investasi/tambah_investasi', 'TransaksiInvestasi::index');
    $routes->post('transaksi_investasi/tambah_data_transaksi', 'TransaksiInvestasi::tambahDataTransaksi');
    $routes->post('transaksi_investasi/ubah_data_transaksi', 'TransaksiInvestasi::ubahDataTransaksi');
    $routes->post('transaksi_investasi/hapus_data_transaksi', 'TransaksiInvestasi::hapusDataTransaksi');
    $routes->get('transaksi_investasi/migrasi/(:segment)', 'TransaksiInvestasi::migrasi/$1');
    $routes->get('transaksi_investasi/cetak_surat_saham/(:segment)/(:segment)', 'TransaksiInvestasi::cetakSuratSaham/$1/$2');
    $routes->get('transaksi_investasi/cetak_surat_keterangan/(:segment)', 'TransaksiInvestasi::cetakSuratKeterangan/$1');

    // Penarikan Investasi
    $routes->get('transaksi_investasi/penarikan_investasi', 'PenarikanInvestasi::index');
    $routes->get('penarikan_investasi', 'PenarikanInvestasi::index');
    $routes->get('penarikan_investasi/getData/(:segment)', 'PenarikanInvestasi::getData/$1');
    $routes->post('penarikan_investasi/getDataID', 'PenarikanInvestasi::getDataID');
    $routes->post('penarikan_investasi/tambah_data_penarikan', 'PenarikanInvestasi::tambahDataPenarikan');
    $routes->post('penarikan_investasi/update', 'PenarikanInvestasi::update');
    $routes->post('penarikan_investasi/delete', 'PenarikanInvestasi::delete');
    $routes->post('penarikan_investasi/getJumlah', 'PenarikanInvestasi::getJumlah');

    // Saham Umum
    $routes->get('transaksi_investasi/saham_umum', 'SahamUmum::index');
    $routes->get('saham_umum', 'SahamUmum::index');
    $routes->get('saham_umum/getData/(:segment)', 'SahamUmum::getData/$1');
    $routes->post('saham_umum/getDataID', 'SahamUmum::getDataID');

    // Saldo Harian
    $routes->get('saldo_harian', 'SaldoHarian::index');
    $routes->post('saldo_harian', 'SaldoHarian::index');
    $routes->get('saldo_harian/update_saldo/(:segment)', 'SaldoHarian::updateSaldo/$1');
    $routes->get('saldo_harian/update_saldo/(:segment)/(:num)/(:num)', 'SaldoHarian::updateSaldo/$1/$2/$3');

    // Sisa Hasil Usaha (SHU)
    $routes->get('sisa_hasil_usaha', 'SisaHasilUsaha::index');
    $routes->post('sisa_hasil_usaha/tambah_data_shu', 'SisaHasilUsaha::tambahDataShu');
    $routes->post('sisa_hasil_usaha/ubah_data_shu', 'SisaHasilUsaha::ubahDataShu');
    $routes->post('sisa_hasil_usaha/hapus_data_shu', 'SisaHasilUsaha::hapusDataShu');

    // Distribusi dan Porsi SHU
    $routes->get('distribusi_dan_porsi', 'DistribusiDanPorsi::index');
    $routes->post('distribusi_dan_porsi', 'DistribusiDanPorsi::index');

    // Bagi Hasil SHU
    $routes->get('bagi_hasil', 'BagiHasil::index');
    $routes->post('bagi_hasil', 'BagiHasil::index');
    $routes->post('bagi_hasil/lihat_data', 'BagiHasil::lihatData');

    // Label Surat (Alias Sidebar)
    $routes->get('label_surat', 'Laporan::labelSurat');

    // Laporan & Ekspor (XLS / PDF)
    $routes->group('laporan', static function (RouteCollection $routes) {
        $routes->get('cetak_profil_pemegang_saham', 'Laporan::cetakProfilPemegangSaham');
        $routes->get('konfersi_profil_pemegang_saham', 'Laporan::konfersiProfilPemegangSaham');
        $routes->get('export_profil_pemegang_saham', 'Laporan::exportProfilPemegangSaham');

        $routes->get('saldo_awal_tahun', 'Laporan::saldoAwalTahun');
        $routes->post('cetak_saldo_awal_tahun', 'Laporan::cetakSaldoAwalTahun');
        $routes->get('konfersi_saldo_awal_tahun/(:segment)', 'Laporan::konfersiSaldoAwalTahun/$1');
        $routes->get('export_saldo_awal_tahun/(:segment)', 'Laporan::exportSaldoAwalTahun/$1');

        $routes->get('cetak_transaksi_terkini', 'Laporan::cetakTransaksiTerkini');
        $routes->get('konfersi_transaksi_terkini/(:segment)/(:segment)/(:segment)', 'Laporan::konfersiTransaksiTerkini/$1/$2/$3');
        $routes->get('export_transaksi_terkini/(:segment)/(:segment)/(:segment)', 'Laporan::exportTransaksiTerkini/$1/$2/$3');

        $routes->get('transaksi_bulanan', 'Laporan::transaksiBulanan');
        $routes->post('cetak_transaksi_invest_bln', 'Laporan::cetakTransaksiInvestBln');
        $routes->get('konfersi_transaksi_invest_bln/(:segment)/(:segment)/(:segment)', 'Laporan::konfersiTransaksiInvestBln/$1/$2/$3');
        $routes->get('export_transaksi_invest_bln/(:segment)/(:segment)/(:segment)', 'Laporan::exportTransaksiInvestBln/$1/$2/$3');

        $routes->get('penarikan_investasi', 'Laporan::penarikanInvestasi');
        $routes->post('cetak_penarikan_investasi', 'Laporan::cetakPenarikanInvestasi');
        $routes->get('konfersi_penarikan_investasi/(:segment)', 'Laporan::konfersiPenarikanInvestasi/$1');
        $routes->get('export_penarikan_investasi/(:segment)', 'Laporan::exportPenarikanInvestasi/$1');

        $routes->get('saldo_harian', 'Laporan::saldoHarian');
        $routes->post('cetak_saldo_harian', 'Laporan::cetakSaldoHarian');
        $routes->get('konfersi_saldo_harian/(:segment)', 'Laporan::konfersiSaldoHarian/$1');
        $routes->get('export_saldo_harian/(:segment)', 'Laporan::exportSaldoHarian/$1');

        $routes->get('distribusi_dan_porsi', 'Laporan::distribusiDanPorsi');
        $routes->post('cetak_distribusi_dan_porsi', 'Laporan::cetakDistribusiDanPorsi');
        $routes->get('konfersi_distribusi_dan_porsi/(:segment)', 'Laporan::konfersiDistribusiDanPorsi/$1');
        $routes->get('export_distribusi_dan_porsi/(:segment)', 'Laporan::exportDistribusiDanPorsi/$1');

        $routes->get('bagi_hasil_shu', 'Laporan::bagiHasilShu');
        $routes->post('cetak_bagi_hasil_shu', 'Laporan::cetakBagiHasilShu');
        $routes->get('konfersi_bagi_hasil_shu/(:segment)/(:segment)', 'Laporan::konfersiBagiHasilShu/$1/$2');
        $routes->get('export_bagi_hasil_shu/(:segment)/(:segment)', 'Laporan::exportBagiHasilShu/$1/$2');

        $routes->get('label_surat', 'Laporan::labelSurat');
        $routes->post('cetak_label_surat', 'Laporan::cetakLabelSurat');

        $routes->get('rek_bank_anggota', 'Laporan::rekBankAnggota');
        $routes->get('konfersi_rek_bank_anggota', 'Laporan::konfersiRekBankAnggota');
    });
});

// REST API (Modern api/* dan alias legacy c_api/*)
$routes->group('api', static function (RouteCollection $routes) {
    $routes->get('users', 'Api::users');
    $routes->get('clients', 'Api::clients');
    $routes->get('client/(:segment)', 'Api::clientId/$1');
    $routes->get('saldo-harian/(:segment)', 'Api::saldoHarianId/$1');
    $routes->get('dividen/(:segment)', 'Api::dividenId/$1');
    $routes->get('transaksi/(:segment)', 'Api::transaksiId/$1');
    $routes->get('transaksi-saham/(:segment)', 'Api::transaksiSaham/$1');
    $routes->get('saldo-investasi/(:segment)/(:segment)', 'Api::saldoInvestasi/$1/$2');
});

$routes->group('c_api', static function (RouteCollection $routes) {
    $routes->get('Users', 'Api::users');
    $routes->get('users', 'Api::users');
    $routes->get('Clients', 'Api::clients');
    $routes->get('clients', 'Api::clients');
    $routes->get('ClientId/(:segment)', 'Api::clientId/$1');
    $routes->get('client_id/(:segment)', 'Api::clientId/$1');
    $routes->get('SaldoHarianId/(:segment)', 'Api::saldoHarianId/$1');
    $routes->get('DividenId/(:segment)', 'Api::dividenId/$1');
    $routes->get('TransaksiId/(:segment)', 'Api::transaksiId/$1');
    $routes->get('TransaksiSaham/(:segment)', 'Api::transaksiSaham/$1');
    $routes->get('SaldoInvestasi/(:segment)/(:segment)', 'Api::saldoInvestasi/$1/$2');
});

// Halaman QR Publik & Endpoint Cron
$routes->get('surat_saham/index/(:segment)', 'SuratSaham::index/$1');
$routes->get('penarikan/index/(:segment)', 'Penarikan::index/$1');
$routes->get('penambahan_saldo', 'PenambahanSaldo::index');

// Khusus Super User (Manajemen User & Aktivitas)
$routes->group('users', ['filter' => ['auth', 'role:super_user']], static function (RouteCollection $routes) {
    $routes->get('list', 'Users::list');
    $routes->get('activity', 'Users::activity');
    $routes->post('tambah', 'Users::tambah');
    $routes->post('hapus', 'Users::hapus');
    $routes->post('reset', 'Users::reset');
});
