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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api', 'middleware' => \Barryvdh\Cors\HandleCors::class], function () use ($router) {
    $router->group(['prefix' => 'questions', 'middleware' => 'authData'], function () use ($router) {
        $router->get('', ['uses' => 'QuestionController@list']);

        $router->get('{id}', ['uses' => 'QuestionController@view']);

        $router->post('', ['uses' => 'QuestionController@create']);

        $router->delete('{id}', ['uses' => 'QuestionController@delete']);

        $router->put('{id}', ['uses' => 'QuestionController@update']);

        $router->post('{id}/answer', ['uses' => 'QuestionController@answer']);
    });

    $router->group(['prefix' => 'answers'], function () use ($router) {
        $router->delete('{id}', ['uses' => 'AnswerController@delete']);

        $router->put('{id}', ['uses' => 'AnswerController@update']);
    });
});
