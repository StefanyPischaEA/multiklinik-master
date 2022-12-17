<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group([
    'prefix' => 'auth'
], function () use ($router) {
    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
});

$router->group([
    'prefix' => 'pasien'
], function () use ($router) {
    $router->get('/', 'PasienController@list');
    $router->get('/{id}', 'PasienController@detail');
    $router->post('/{id}', 'PasienController@update');
});

$router->get('klinik','MasterController@clinic');
$router->get('dokter','MasterController@dokter');
$router->get('tanggal-pemeriksaan','MasterController@tanggal');
$router->get('waktu-pemeriksaan','MasterController@waktu');

$router->group([
    'prefix' => 'janji-periksa'
], function () use ($router) {
    $router->get('/', 'JanjiPeriksaController@list');
    $router->post('/praktik-selesai', 'JanjiPeriksaController@praktikSelesai');
    $router->get('/{id}', 'JanjiPeriksaController@detail');
    $router->post('/{id}', 'JanjiPeriksaController@update');
});
