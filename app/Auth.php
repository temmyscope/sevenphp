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

		public static function getValuesFromToken($token)
		{
			$app = app();
			try {
				$data = JWT::decode($token, $app->get('PUBLIC_KEY'), [ $app->get('JWT') ]);
			} catch (\Exception $e) {
				$data = [];
			}
			return $data;
		}

		public static function isValid($token): bool
		{
			$decoded = self::decomposeToken( $token );
			if ( !empty ( $decoded ) ){
				return true;
			}
			return false;
		}
}