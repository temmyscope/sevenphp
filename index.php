<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

/*
|---------------------------------------------------------------------------|
| Register The Auto Loader 													|
|---------------------------------------------------------------------------|
|
*/
require __DIR__.'/vendor/autoload.php';

/**
* @package  SevenPHP
* @author   Elisha Temiloluwa <temmyscope@protonmail.com>
|-------------------------------------------------------------------------------|
|	SevenPHP by Elisha Temiloluwa a.k.a TemmyScope 								|
|-------------------------------------------------------------------------------|
*/

$request = request();

use Seven\Router\Route;

$route = new Route('App\Controllers');

$route->enableCache(__DIR__.'/cache');

$route->setFallback(function(){
	response("Error 404, Method not Found.", 404);
}, Route::NOT_FOUND);
$route->setFallback(function(){
	response("Error 404, Method not Allowed.", 405);
}, Route::METHOD_NOT_ALLOWED);
$route->get('/', function(){
	response("api version 1, codename V1", 200);
});

$route->post('/login', [ AuthController::class, "login" ]);
$route->post('/register', [ AuthController::class, "register" ]);
$route->post('/activate', [ AuthController::class, "activate" ]);

$route->group(['name' => 'auth', 'middleware' => [ App\Controllers\AuthController::class, "index" ], 'inject' => [ $request ] ],  function($route){
	$route->get('/search', [ SearchController::class, 'index' ]);
	$route->get('/home',  [ HomeController::class, 'index' ]);
	$route->get('/logout', [ AuthController::class, "logout" ]);
});

$route->run($_SERVER['REQUEST_METHOD'], $_SERVER['PATH_INFO'] ?? '/');