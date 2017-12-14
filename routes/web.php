<?php

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

$router->get('/', function () use ($router)
{
    return $router->app->version();
});

$router->get('users', 'UserController@index');
$router->post('user/store', 'UserController@store');
$router->put('user/update/{id}', 'UserController@update');
$router->delete('user/delete/{id}', 'UserController@delete');
