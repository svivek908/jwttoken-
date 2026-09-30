<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('api/login', 'AuthController::login');
$routes->get('upload', 'UploadController::index');
$routes->post('upload-files', 'UploadController::uploadFiles');
$routes->get('uploads/(:any)', 'UploadController::showImage/$1');
