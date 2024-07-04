<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/registrasi', 'RegistrasiController::registrasi');
$routes->post('/login', 'LoginController::login');
$routes->post('/logout', 'LoginController::logout');

$routes->get('/profile', 'ProfileController::profile');
$routes->post('/profile', 'ProfileController::ubah');
$routes->delete('/profile', 'ProfileController::hapus');

$routes->group('produk', function ($routes) {
    $routes->post('/', 'ProdukController::create');
    $routes->get('/', 'ProdukController::list');
    $routes->get('(:segment)', 'ProdukController::detail/$1');
    $routes->put('(:segment)', 'ProdukController::ubah/$1');
    $routes->post('(:segment)/update', 'ProdukController::ubah/$1');
    $routes->delete('(:segment)', 'ProdukController::hapus/$1');
});
