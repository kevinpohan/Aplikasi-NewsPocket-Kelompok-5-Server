<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function ($routes) {
    $routes->post('registrasi', 'AuthController::register');
    $routes->post('login', 'AuthController::login');

    // Resource Controller untuk Bookmarks
    $routes->get('bookmarks', 'BookmarkController::index');
    $routes->post('bookmarks', 'BookmarkController::create');
    $routes->put('bookmarks/(:num)', 'BookmarkController::update/$1'); // Update Note
    $routes->delete('bookmarks/(:num)', 'BookmarkController::delete/$1');
    $routes->get('berita', 'BeritaController::search');
    $routes->get('profile', 'ProfileController::index');
    $routes->put('profile', 'ProfileController::update');
    $routes->post('profile/photo', 'ProfileController::uploadPhoto');
});
