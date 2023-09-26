<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/musicplayer', 'MusicController::index');
$routes->get('/', 'MusicController::index');
$routes->post('/upload', 'MusicController::upload');
$routes->post('/create', 'MusicController::create');
$routes->get('/playlist/(:any)', 'MusicController::playlist/$1');
$routes->get('/search', 'MusicController::search');
$routes->post('/upload', 'MusicController::upload');
$routes->post('/add', 'MusicController::add');

