<?php
namespace App;

use App\Providers\Model;
use \Firebase\JWT\JWT;

class Auth extends Model{

    protected static $table = 'users';
    protected static $fulltext = [];
    
    public static function generateUserToken(object $user, bool $long = false): string
	{
        $app = app();
		$iat = time(); //time of token issued at
		$nbf = $iat + 5; //not before in seconds
        $exp = ( $long === true ) ? $iat + 2592000 : $iat + 9000; //expiry time of token in seconds
		$token = [
			"iss" => $app->get('ISSUER'), "aud" => $app->get('AUDIENCE'), "iat" => $iat, "nbf" => $nbf, "exp" => $exp,
			"user_id" => $user->id, "email" => $user->email, "name" => $user->name, "is_verified" => $user->verified
		];
		return self::encryptToken($token);
	}

	public static function encryptToken(Array $data): string
	{
		$app = app();
		return JWT::encode( $data, $app->get('PRIVATE_KEY'), $app->get('JWT') );
	}
	
	public static function getUserId($request) : int
	{
		$decoded = self::decomposeToken($request->getToken());
		if(  !empty ( $decoded ) ){
			return (int)$decoded['user_id'];
		}
		return 0;
	}

	public function getTokenData($request)
	{
		$decoded = self::decomposeToken($request->getToken());
		if(  !empty ( $decoded ) ){
			return $decoded;
		}
		return false;
	}

	public function getUserEmail($request)
	{
		$decoded = self::decomposeToken($request->getToken());
		if(  !empty ( $decoded ) ){
			return $decoded['email'];
		}
		return false;
	}

	public static function deactivateToken(): string
	{
		return "";
	}

	public static function getToken($request)
	{
		return $request->getToken();
	}

	public static function decomposeToken($token)
	{
		$app = app();
		try {
			$data = (array) JWT::decode($token, $app->get('PUBLIC_KEY'), [ $app->get('JWT') ]);
		} catch (\Exception $e) {
			$data = [];
		}
		return $data;
	}

	public static function isLoggedIn($request): bool
	{
		$decoded = self::decomposeToken( $request->getToken() );
		if ( !empty ( $decoded ) ){
			return true;
		}
		return false;
	}

	public static function isAuthorised($request): bool
	{
		$token = Auth::decomposeToken($request->getToken());
		if (!empty($token) && isset($token['auth']) && $token['auth'] > time() ) {
			return true;
		}
		return false;
	}

	public static function getUserAgent($request)
	{
		return $request->get('user_agent');
	}
}