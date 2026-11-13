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
});

// Khusus Super User (Manajemen User & Aktivitas)
$routes->group('users', ['filter' => ['auth', 'role:super_user']], static function (RouteCollection $routes) {
    $routes->get('list', 'Users::list');
    $routes->get('activity', 'Users::activity');
    $routes->post('tambah', 'Users::tambah');
    $routes->post('hapus', 'Users::hapus');
    $routes->post('reset', 'Users::reset');
});
