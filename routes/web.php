<?php
/** 
* @var \Seven\Vars\Router $router 
*/

/*
|-----------------------------------------------------------------------
| Application Routes
|-----------------------------------------------------------------------
| Here is where you can register all of the routes for your application.
*/

$router->use('cors', function() use($router) {

		$router->get(['/content/:id/like', 'home'], function($request, $response){
				return $response->setContent(
					"welcome".$request->params->id	//App\Contents::all()
				)->send();
		});

		$router->all("trash", function($request, $response){
				return $response->setContent("welcome 2") //$request->params->id
				->send();
		});

		$router->post('login', [ AuthController::class, "login" ]);

		$router->post('register', [ AuthController::class, "register" ]);

		$router->post('forgot-password', [ AuthController::class, "forgot_pass" ]);

		$router->use('auth', function() use($router){

				$router->get('author/contents', [ AuthorController::class, 'all']);

				$router->get('content/:id', [ ContentController::class, 'index']);

				$router->use('admin', function() use ($router){
						$router->get('stats', [ ModeratorController::class, 'index' ]);
						$router->post('push/broadcast', [ BroadCastController::class, 'push' ]);
						$router->post('mail/broadcast', [ BroadCastController::class, 'mail' ]);
						$router->delete('user/:id', [ ModeratorController::class, 'delete' ]);
				});
				
				$router->post('search', [ SearchController::class, 'index']);

				$router->post('content', [ ContentController::class, 'create']); //for author

				$router->post('bio', [ UserController::class, 'add_bio']);

				$router->post('social', [ UserController::class, 'add_social']); //facebook, twitter

				$router->post('block/:id', [ BlockController::class, 'index']);

				$router->post('like/:id', [ LikeController::class, 'index']);

				$router->post('comment/:commentId', [ CommentController::class, 'create' ]);

				$router->post('sequel/:id', [ ContentController::class, 'add_sequel' ]);

				$router->put('content/:id', [ ContentController::class, 'update']); //for author

				$router->put('content/:contentId/comment/:commentId', [ CommentController::class, 'hide' ]);//hide comment => author

				$router->delete('content/:id', [ ContentController::class, 'delete']); //for author

				$router->delete('comment/:id', [ CommentController::class, 'update']); //for commenter

		});

});