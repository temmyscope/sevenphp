<?php return array (
  'p' => 
  array (
    'GET' => 
    array (
      1 => 
      array (
        ':id' => 
        array (
          'callable' => 'C:32:"Opis\\Closure\\SerializableClosure":269:{a:5:{s:3:"use";a:0:{}s:8:"function";s:145:"function($request, $response){
				return $response->setContent(
					"welcome".$request->params->id	//App\\Contents::all()
				)->send();
		}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000621a7b32000000003ab14b6c";}}',
          'middlewares' => 
          array (
            0 => 'cors',
          ),
          'params' => 
          array (
            0 => 'id',
          ),
          'route' => 
          array (
            0 => ':id',
          ),
        ),
      ),
      2 => 
      array (
        'content' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\ContentController";i:1;s:5:"index";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'content',
            1 => ':id',
          ),
        ),
      ),
    ),
    'DELETE' => 
    array (
      2 => 
      array (
        'user' => 
        array (
          'callable' => 'a:2:{i:0;s:35:"App\\Controllers\\ModeratorController";i:1;s:6:"delete";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'user',
            1 => ':id',
          ),
        ),
        'content' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\ContentController";i:1;s:6:"delete";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'content',
            1 => ':id',
          ),
        ),
        'comment' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\CommentController";i:1;s:6:"update";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'comment',
            1 => ':id',
          ),
        ),
      ),
    ),
    'POST' => 
    array (
      2 => 
      array (
        'block' => 
        array (
          'callable' => 'a:2:{i:0;s:31:"App\\Controllers\\BlockController";i:1;s:5:"index";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'block',
            1 => ':id',
          ),
        ),
        'like' => 
        array (
          'callable' => 'a:2:{i:0;s:30:"App\\Controllers\\LikeController";i:1;s:5:"index";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'like',
            1 => ':id',
          ),
        ),
        'comment' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\CommentController";i:1;s:6:"create";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'commentId',
          ),
          'route' => 
          array (
            0 => 'comment',
            1 => ':commentId',
          ),
        ),
        'sequel' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\ContentController";i:1;s:10:"add_sequel";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'sequel',
            1 => ':id',
          ),
        ),
      ),
    ),
    'PUT' => 
    array (
      2 => 
      array (
        'content' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\ContentController";i:1;s:6:"update";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'id',
          ),
          'route' => 
          array (
            0 => 'content',
            1 => ':id',
          ),
        ),
      ),
      4 => 
      array (
        'content' => 
        array (
          'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\CommentController";i:1;s:4:"hide";}',
          'middlewares' => 
          array (
            0 => 'cors',
            1 => 'auth',
            2 => 'admin',
          ),
          'params' => 
          array (
            1 => 'contentId',
            3 => 'commentId',
          ),
          'route' => 
          array (
            0 => 'content',
            1 => ':contentId',
            2 => 'comment',
            3 => ':commentId',
          ),
        ),
      ),
    ),
  ),
  'u' => 
  array (
    'GET' => 
    array (
      'home' => 
      array (
        'callable' => 'C:32:"Opis\\Closure\\SerializableClosure":269:{a:5:{s:3:"use";a:0:{}s:8:"function";s:145:"function($request, $response){
				return $response->setContent(
					"welcome".$request->params->id	//App\\Contents::all()
				)->send();
		}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000621a7b32000000003ab14b6c";}}',
        'middlewares' => 
        array (
          0 => 'cors',
        ),
      ),
      'author/contents' => 
      array (
        'callable' => 'a:2:{i:0;s:32:"App\\Controllers\\AuthorController";i:1;s:3:"all";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
        ),
      ),
      'stats' => 
      array (
        'callable' => 'a:2:{i:0;s:35:"App\\Controllers\\ModeratorController";i:1;s:5:"index";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
    ),
    'ALL' => 
    array (
      'trash' => 
      array (
        'callable' => 'C:32:"Opis\\Closure\\SerializableClosure":244:{a:5:{s:3:"use";a:0:{}s:8:"function";s:120:"function($request, $response){
				return $response->setContent("welcome 2") //$request->params->id
				->send();
		}";s:5:"scope";N;s:4:"this";N;s:4:"self";s:32:"00000000621a7b32000000003ab14b6c";}}',
        'middlewares' => 
        array (
          0 => 'cors',
        ),
      ),
    ),
    'POST' => 
    array (
      'login' => 
      array (
        'callable' => 'a:2:{i:0;s:30:"App\\Controllers\\AuthController";i:1;s:5:"login";}',
        'middlewares' => 
        array (
          0 => 'cors',
        ),
      ),
      'register' => 
      array (
        'callable' => 'a:2:{i:0;s:30:"App\\Controllers\\AuthController";i:1;s:8:"register";}',
        'middlewares' => 
        array (
          0 => 'cors',
        ),
      ),
      'forgot-password' => 
      array (
        'callable' => 'a:2:{i:0;s:30:"App\\Controllers\\AuthController";i:1;s:11:"forgot_pass";}',
        'middlewares' => 
        array (
          0 => 'cors',
        ),
      ),
      'push/broadcast' => 
      array (
        'callable' => 'a:2:{i:0;s:35:"App\\Controllers\\BroadCastController";i:1;s:4:"push";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
      'mail/broadcast' => 
      array (
        'callable' => 'a:2:{i:0;s:35:"App\\Controllers\\BroadCastController";i:1;s:4:"mail";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
      'search' => 
      array (
        'callable' => 'a:2:{i:0;s:32:"App\\Controllers\\SearchController";i:1;s:5:"index";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
      'content' => 
      array (
        'callable' => 'a:2:{i:0;s:33:"App\\Controllers\\ContentController";i:1;s:6:"create";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
      'bio' => 
      array (
        'callable' => 'a:2:{i:0;s:30:"App\\Controllers\\UserController";i:1;s:7:"add_bio";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
      'social' => 
      array (
        'callable' => 'a:2:{i:0;s:30:"App\\Controllers\\UserController";i:1;s:10:"add_social";}',
        'middlewares' => 
        array (
          0 => 'cors',
          1 => 'auth',
          2 => 'admin',
        ),
      ),
    ),
  ),
);