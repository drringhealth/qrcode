<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Admin Routes
$routes->group('admin', ['namespace' => 'App\\Controllers\\Admin'], function ($routes) {
    // Auth
    $routes->post('login', 'AuthController::login');
    $routes->get('login', 'AuthController::login');
    $routes->get('logout', 'AuthController::logout');

    // Dashboard
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('users', 'DashboardController::users');
    $routes->get('pets', 'DashboardController::pets');
    $routes->get('orders', 'DashboardController::orders');
    $routes->get('qrtags', 'DashboardController::qrTags');
    $routes->get('audit-logs', 'DashboardController::auditLogs');

    // Products
    $routes->get('products', 'ProductController::index');
    $routes->get('products/create', 'ProductController::create');
    $routes->post('products', 'ProductController::create');
    $routes->get('products/(:num)/edit', 'ProductController::edit/$1');
    $routes->post('products/(:num)', 'ProductController::edit/$1');
    $routes->delete('products/(:num)', 'ProductController::delete/$1');

    // QR Tags
    $routes->get('qrtags/generate', 'QrTagController::generate');
    $routes->post('qrtags/generate', 'QrTagController::generate');
    $routes->get('qrtags/export', 'QrTagController::export');
});

// API Routes
$routes->group('api', ['namespace' => 'App\\Controllers\\Api'], function ($routes) {
    // Auth
    $routes->post('auth/send-otp', 'AuthController::sendOtp');
    $routes->post('auth/verify-otp', 'AuthController::verifyOtp');
    $routes->post('auth/register', 'AuthController::register');
    $routes->post('auth/login', 'AuthController::login');

    // Products
    $routes->get('products', 'ProductController::index');
    $routes->get('products/(:num)', 'ProductController::show/$1');
    $routes->get('products/category/(:num)', 'ProductController::byCategory/$1');

    // Pets
    $routes->get('pets', 'PetController::index');
    $routes->get('pets/(:num)', 'PetController::show/$1');
    $routes->post('pets', 'PetController::create');
    $routes->put('pets/(:num)', 'PetController::update/$1');
    $routes->delete('pets/(:num)', 'PetController::delete/$1');
    $routes->post('pets/(:num)/vaccinations', 'PetController::addVaccination/$1');

    // Orders
    $routes->get('orders', 'OrderController::index');
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->post('orders', 'OrderController::create');

    // Finder
    $routes->get('finder/pet/(:any)', 'FinderController::lostPet/$1');
    $routes->post('finder/pet/(:num)/message', 'FinderController::sendMessage/$1');
});

// Public Routes
$routes->get('/', 'Home::index');
$routes->get('pet/(:any)', 'Home::viewPet/$1');
$routes->post('pet/(:num)/scan', 'Home::logScan/$1');

// Health check
$routes->get('health', function () {
    return json_encode(['status' => 'ok', 'timestamp' => date('Y-m-d H:i:s')]);
});
