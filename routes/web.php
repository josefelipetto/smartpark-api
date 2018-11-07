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
    return 'Unauthorized';
});


$router->group(['prefix' => 'api'], function () use ($router){

    $router->group(['prefix' => 'user','middleware' => 'jwt.auth'], function () use($router){
        $router->get('{id}','UserController@show');
        $router->post('/','UserController@store');
        $router->put('{id}','UserController@update');
        $router->delete('{id}','UserController@destroy');
        $router->post('{id}/card','CreditCardController@store');
        $router->delete('{user_id}/card/{card_id}','CreditCardController@destroy');

        $router->get('{user_id}/tradings','MovimentosController@show');
        $router->post('{user_id}/trade','MovimentosController@store');
        $router->get('{user_id}/balance','MovimentosController@balance');
    });

    $router->post('auth/login','AuthController@authenticate');

});

