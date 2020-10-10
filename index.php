<?php
use Seven\Router\Router;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Auth;

$start = microtime(true);

/*
|---------------------------------------------------------------------------|
| Register The Auto Loader 																									|
|---------------------------------------------------------------------------|
|
*/
require __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$response = new Response();

/**
* @package  SevenPHP
* @author   Elisha Temiloluwa <temmyscope@protonmail.com>
|-------------------------------------------------------------------------------|
|	SevenPHP by Elisha Temiloluwa a.k.a TemmyScope 																|
|-------------------------------------------------------------------------------|
*/

$start = microtime(true);

$router = new Router('App\Controllers');

$router->enableCache(__DIR__.'/cache');

$router->registerProviders($request, $response);

$router->middleware('cors', function($request, $response, $next){
		$headers = [
	      'Access-Control-Allow-Origin'      => '*',
	      'Access-Control-Allow-Methods'     => '*',
	      'Access-Control-Allow-Credentials' => 'true',
	      'Access-Control-Max-Age'           => '86400',
	      'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
	  ];
	  if ($request->isMethod('OPTIONS')){
	      return $response->setHeaders($headers)
	      ->setContent('{"method":"OPTIONS"}')
	      ->setStatusCode(200)
	      ->send();
	  }
	  foreach($headers as $key => $value){
	      $response->headers->set($key, $value);
	  }
		$next($request, $response);
});

$router->middleware('auth', function($request, $response, $next){
		$token = $request->headers->get('Authorization');
		if ( !$token || Auth::isValid($token) ) {
				return $response->setContent('Unauthorized.')
				->setStatusCode(401)
				->send();
		}
		$request->userId = Auth::getValuesFromToken($token)->user_id;
		$next->handle($request);
});

$router->middleware('admin', function($request, $response, $next){
		$token = $request->headers->get('Authorization');
		$user = App\User::findby([ 'id' =>$request->userId, 'type'=> 'admin', 'deleted'=> 'false', 'verified'=> 'false']);
		
		if( empty($user) ){
				return $response->setContent('Unauthorized.')
	      ->setStatusCode(401)
	      ->send();
		}
		$next($request, $response);
});

require __DIR__.'/routes/web.php';

$router->run();

echo "<pre>",microtime(true)-$start;