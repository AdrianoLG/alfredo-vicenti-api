<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => '/api'], function () use ($router) {
    $router->group(['prefix' => '/user'], function () use ($router) {
        $router->post('/', 'UserController@createUser');
        $router->post('/login', 'UserController@loginUser');
        $router->put('/password/{user_id}', 'UserController@updateUserPassword');
        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->get('/{user_id}', 'UserController@getUser');
            $router->put('/password-token', 'UserController@updateUserPasswordToken');
            $router->put('/reset-password-token', 'UserController@resetUserPasswordToken');
            $router->put('/{user_id}', 'UserController@updateUserData');
            $router->post('/exists', 'UserController@userExists');
            $router->delete('/{user_id}', 'UserController@removeUser');
        });
    });
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/books/user/{user_id}', 'BookController@getBooks');
        $router->get('/books/group/{group_id}/user/{user_id}', 'BookController@getGroupBooks');
        $router->group(['prefix' => '/book'], function () use ($router) {
            $router->post('/', 'BookController@createBook');
            $router->get('/{book_id}/user/{user_id}', 'BookController@getBook');
            $router->put('/{book_id}', 'BookController@updateBook');
            $router->delete('/{book_id}/user/{user_id}', 'BookController@removeBook');
        });
        $router->get('/groups/user/{user_id}', 'GroupController@getGroups');
        $router->group(['prefix' => '/group'], function () use ($router) {
            $router->post('/', 'GroupController@createGroup');
            $router->post('/user', 'GroupController@createGroupUser');
            $router->post('/user/color', 'GroupController@changeGroupColor');
            $router->get('/{group_id}/user/{user_id}', 'GroupController@getGroup');
            $router->put('/{group_id}', 'GroupController@updateGroup');
            $router->put('/user/{group_user_id}', 'GroupController@updateGroupUser');
            $router->delete('/{group_id}/admin/{admin_id}', 'GroupController@removeGroup');
            $router->delete('/{group_id}/user/{user_id}', 'GroupController@removeGroupUser');
        });
    });
});
