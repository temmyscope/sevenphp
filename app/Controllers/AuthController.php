<?php
namespace App\Controllers;

use App\{Auth, Users};
use App\Providers\{Notification, Strings, Response};
use Seven\Vars\{Validation};

class AuthController extends Controller{

	public function ForgotPassword(Notification $notify)
	{
		global $request;
		$validation = Validation::init($request->all() )->rules([
			'email' => [ 'display' => 'E-mail', 'required' => true, 'is_email' => true ]
		]);
		if($validation->passed()){
			$user = Users::findfirst([ 'email' => $request->get('email') ]);
			if( !empty($user) ){
				$salt = app()->get('APP_SALT');
				$tmp_password = Strings::rand(12);
				Users::update([ 'password' => Strings::hash($salt.$tmp_password) ], ['id' => $user->id ]);
				$notify->ForgotPassword($request->get('email'), $tmp_password );
				$response = [ "message" => "A new password has been sent to your E-mail.", "status" => 2 ];
			    return Response::send($response);
			}
			return Response::send([ 'status' => 3, 'message' => 'There is no account with this email' ]);
		}
		return Response::send([ 'status' => 3, 'message' => $validation->errors()[0] ]);
	}

	public function activate(Strings $strings){
		global $request;
		$validation = Validation::init( $request->all() )->rules([
			'email' => ['display' => 'E-mail', 'required' => true, 'is_email' => true ],
			'activation_key' => [ 'display' => 'Activation Key', 'required' => true ],
		]);
		if ( $validation->passed() ) {
			$user = Users::findfirst(['email' => $request->get('email'), 'activation' => $request->get('activation_key') , 'verified' => 'false' ]);
			if (empty($user)) {
				return Response::send([ "message" => "This account does not exist.", "status" => 3 ]);
			}
			Users::update(['verified' => 'true'], ['id' =>  $user->id ]);
			return Response::send([ "message" => "Your Account has been Activated.", "status" => 2 ]);
		}
		return Response::send([ 'message' => $validation->errors()[0] ?? "This activation details are invalid.", 'status' => 3 ]);
	}

	public function Login(Strings $str){
		global $request;
		$validation = Validation::init( $request->all() )->rules([
			'email' => ['display' => 'E-mail', 'required' => true, 'is_email' => true ],
			'password' => [ 'display' => 'Password', 'required' => true ]
		]);
		if ( $validation->passed() ) {
			$user = Users::findfirst([ 'email' => $request->get('email') ]);
			$salt = app()->get('APP_SALT');
			if ( !empty($user) && Strings::verify_hash($salt.$request->get('password'), $user->password) ) {
				$remember = ( $request->get('remember_me') != null) ? true : false;
				$jwt = Auth::generateUserToken( $user, $remember );
				if( $remember ){
					Users::setTable('user_sessions')->insert([
						'session' => $jwt, 'user_agent' => $request->get('user_agent'), 'push_token' => $request->get('push_token') ?? "",
						'user_id' => $user->id, 'created_at' => Strings::time_from_string('now')
					]);
				}
				$response = [ "message" => "Successful login.", "status" => 1, "jwt" => $jwt, "email" => $user->email, "name" => $user->name ];
				return Response::send($response, 201);
			}
			return Response::send([ "message" => "login failed. Invalid Login Credentials.", "status" => 3 ]);
		}
		return Response::send([ $validation->errors() ], 400);
	}

	public function index(\Closure $next)
	{
		global $request;
		if ( Auth::isLoggedIn( $request) ) {
			return $next();
		}
		$token =  $request->getToken();
		if (empty($token)) return Response::send([ "message" => "You are not logged in"], 401);

		$session = Auth::setTable('user_sessions')->findfirst([ 'session' => $token, 'deleted' => 'false' ]);
		if ( !empty( $session ) ) {
			$user = Users::findfirst(['id' => $session->user_id ]);
			$jwt = Auth::generateUserToken( $user, true );
			Users::setTable('user_sessions')->update([ 'session' => $jwt ],[ 'id' => $session->id ]);
			return Response::send([ "message" => "Successful login.", "jwt" => $jwt, "email" => $user->email, "name" => $user->name ], 200);
		}
		return Response::send([ "Your session token has expired" ], 401);
	}

	public function Logout($req)
	{
		$decoded = Auth::decomposeToken( $request->getToken() );
		Auth::setTable('user_sessions')->delete([
			'session' => $request->getToken(), 'user_id' => $decoded['user_id']
		]);
		return Response::send([ "message" => "You have been logged out."], 200);
	}

	public function register(Strings $strings, Notification $notify)
	{
		global $request;
		$validation = Validation::init($request->all())->rules([
			'name' => [ 'required' => true ],
			'email' => [ 'required' => true, 'is_email' => true ],
			'password' => [ 'required' => true, 'is_same_as' => 'verify_password' ]
		]);
		if ( $validation->passed() ) {
			if ( Users::count('email', [ 'email' => $request->get('email') ]) > 0 ) {
				return Response::send(['status'=> 3, "message" => "An account with this email already exists." ]);
			}
			$activation_key = Strings::makeSafe($strings->fixed_length_token(64));
			$salt = app()->get('APP_SALT');
			$user = Users::insert([ 'name' => $request->get('name'), 'email' => $request->get('email'), 'password' => Strings::hash($salt.$request->get('password')), 
				'activation' => $activation_key, 'created_at' => Strings::time_from_string()
			]);
			if ( is_numeric($user) && $notify->AccountCreated($request->get('email'), $activation_key) ) {
				return Response::send([ "status" => 2, "message" => "Your account has beeen created. check your e-mail to activate your account." ]);
			}
		}
		return Response::send([ $validation->errors() ], 400);
	}
}