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

/* @var \Laravel\Lumen\Routing\Router $router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'user'], function () use($router){
   $router->get('{id}','UserController@show');
   $router->post('/','UserController@store');
   $router->put('{id}','UserController@update');
   $router->delete('{id}','UserController@destroy');
   $router->post('{id}/cartao','CreditCardController@store');
   $router->delete('{user_id}/cartao/{card_id}','CreditCardController@destroy');
});

