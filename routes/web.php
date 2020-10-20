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

$router->group(['prefix' => '/api'], function () use ($router) {
    $router->group(['prefix' => '/user'], function () use ($router) {
        $router->post('/', 'UserController@createUser');
        $router->post('/login', 'UserController@loginUser');
        $router->put('/password/{id}', 'UserController@updateUserPassword');
        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->put('/password-token', 'UserController@updateUserPasswordToken');
            $router->put('/reset-password-token', 'UserController@resetUserPasswordToken');
            $router->get('/{id}', 'UserController@getUser');
            $router->delete('/{id}', 'UserController@removeUser');
        });
    });
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/books', 'BookController@getBooks');
        $router->group(['prefix' => '/book'], function () use ($router) {
            $router->post('/', 'BookController@createBook');
            $router->put('/{id}', 'BookController@updateBook');
            $router->delete('/{id}', 'BookController@removeBook');
        });
        $router->get('/groups', 'GroupController@getGroups');
        $router->group(['prefix' => '/group'], function () use ($router) {
            $router->post('/', 'GroupController@createGroup');
            $router->post('/user', 'GroupController@createGroupUser');
            $router->get('/{id}', 'GroupController@getGroup');
            $router->put('/{id}', 'GroupController@updateGroup');
            $router->put('/user/{group_user_id}', 'GroupController@updateGroupUser');
            $router->delete('/{id}', 'GroupController@removeGroup');
            $router->delete('/user/{id}', 'GroupController@removeGroupUser');
        });
    });
});
