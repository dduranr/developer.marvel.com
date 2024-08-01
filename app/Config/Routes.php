<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Front\Frontend::index');
$routes->get('/buscar', 'Front\Frontend::buscar');



/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Auth
$routes->group('/auth', ['namespace' => 'App\Controllers\Auth'], function ($routes) {
    $routes->get('login', 'Auth::create');
    $routes->get('logout', 'Auth::logout', ["filter" => "myauth"]);
    $routes->get('welcome', 'Auth::index', ["filter" => "myauth"]);
    $routes->post('store', 'Auth::store');
});



// Backend
$routes->group('/backend', ['namespace' => 'App\Controllers\Back'], function ($routes) {
    $routes->get('users/index', 'User::index', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->get('users/create', 'User::create', ["filter" => "myauth:SuperAdmin"]);
    $routes->post('users/store', 'User::store', ["filter" => "myauth:SuperAdmin"]);
    $routes->get('users/edit/(:num)', 'User::edit/$1', ["filter" => "myauth:SuperAdmin"]);
    $routes->post('users/update/(:num)', 'User::update/$1', ["filter" => "myauth:SuperAdmin"]);
    $routes->post('users/delete', 'User::delete', ["filter" => "myauth:SuperAdmin"]);
    $routes->get('users/register', 'User::register');

    // Heroes
    $routes->get('heroes/index', 'Heroes::index', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->get('heroes/create', 'Heroes::create', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->post('heroes/store', 'Heroes::store', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->get('heroes/edit/(:num)', 'Heroes::edit/$1', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->post('heroes/update/(:num)', 'Heroes::update/$1', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->post('heroes/delete', 'Heroes::delete', ["filter" => "myauth:SuperAdmin,Admin"]);
    $routes->get('heroes/import', 'Heroes::import', ["filter" => "myauth:SuperAdmin,Admin"]);

    // Configuraciones
    $routes->get('configuraciones/index', 'Configuraciones::index', ["filter" => "myauth:SuperAdmin"]);
    $routes->get('configuraciones/create', 'Configuraciones::create', ["filter" => "myauth:SuperAdmin"]);
    $routes->post('configuraciones/store', 'Configuraciones::store', ["filter" => "myauth:SuperAdmin"]);
    $routes->get('configuraciones/edit/(:num)', 'Configuraciones::edit/$1', ["filter" => "myauth:SuperAdmin"]);
    $routes->post('configuraciones/update/(:num)', 'Configuraciones::update/$1', ["filter" => "myauth:SuperAdmin"]);
    $routes->post('configuraciones/delete', 'Configuraciones::delete', ["filter" => "myauth:SuperAdmin"]);
});


// Datatables
$routes->group('/datatables', ['namespace' => 'App\Controllers\Datatables', "filter" => "myauth:SuperAdmin,Admin"], function ($routes) {
    $routes->get('users', 'Datatables::users');
    $routes->get('heroes', 'Datatables::heroes');
    $routes->get('configuraciones', 'Datatables::configuraciones');
});
