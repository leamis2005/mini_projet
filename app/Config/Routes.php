<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');

$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('employe', ['filter' => ['auth', 'role:employe']], static function ($routes) {
	$routes->get('/', 'EmployeController::dashboard');
	$routes->get('demandes', 'EmployeController::demandes');
	$routes->get('demandes/create', 'EmployeController::create');
	$routes->post('demandes', 'EmployeController::store');
	$routes->post('demandes/(:num)/annuler', 'EmployeController::annuler/$1');
	$routes->get('profil', 'EmployeController::profil');
	$routes->post('profil', 'EmployeController::updateProfil');
});

$routes->group('rh', ['filter' => ['auth', 'role:rh']], static function ($routes) {
	$routes->get('/', 'RhController::index');
	$routes->get('demandes', 'RhController::index');
	$routes->post('demandes/(:num)/approuver', 'RhController::approuver/$1');
	$routes->post('demandes/(:num)/refuser', 'RhController::refuser/$1');
	$routes->get('soldes', 'RhController::soldes');
});

$routes->group('admin', ['filter' => ['auth', 'admin']], static function ($routes) {
	$routes->get('/', 'AdminController::dashboard');
	$routes->get('employes', 'AdminController::employes');
	$routes->post('employes', 'AdminController::employeCreate');
	$routes->get('employes/(:num)/edit', 'AdminController::employeEdit/$1');
	$routes->post('employes/(:num)', 'AdminController::employeUpdate/$1');
	$routes->post('employes/(:num)/toggle', 'AdminController::employeToggle/$1');

	$routes->get('departements', 'AdminController::departements');
	$routes->post('departements', 'AdminController::departementCreate');
	$routes->get('departements/(:num)/edit', 'AdminController::departementEdit/$1');
	$routes->post('departements/(:num)', 'AdminController::departementUpdate/$1');
	$routes->post('departements/(:num)/delete', 'AdminController::departementDelete/$1');

	$routes->get('types-conge', 'AdminController::typesConge');
	$routes->post('types-conge', 'AdminController::typeCongeCreate');
	$routes->get('types-conge/(:num)/edit', 'AdminController::typeCongeEdit/$1');
	$routes->post('types-conge/(:num)', 'AdminController::typeCongeUpdate/$1');
	$routes->post('types-conge/(:num)/delete', 'AdminController::typeCongeDelete/$1');

	$routes->get('soldes', 'AdminController::soldes');
	$routes->post('soldes', 'AdminController::soldesAdjust');

	$routes->get('historique', 'AdminController::historique');
});
