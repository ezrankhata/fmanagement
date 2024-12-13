<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('file-upload', 'FileUpload::index'); // Load the upload form
$routes->post('file-upload', 'FileUpload::upload'); // Handle the file upload

$routes->get('file-upload/view-files', 'FileUpload::viewFiles'); // View uploaded files

// Edit and update routes for specific files
$routes->get('file-upload/edit/(:num)', 'FileUpload::edit/$1'); // Edit file metadata
$routes->post('file-upload/update/(:num)', 'FileUpload::update/$1'); // Update file metadata
$routes->delete('/file-upload/delete/(:segment)', 'FileUpload::delete/$1');
$routes->post('/file-upload/bulk-delete', 'FileUpload::bulkDelete');



$routes->group('api', function($routes) {
    $routes->post('upload', 'ApiController::upload');       // Upload file
    $routes->get('files', 'ApiController::viewFiles');      // View all files
    $routes->delete('files/(:num)', 'ApiController::delete/$1'); // Delete a file by ID
});
