<?php
namespace App\Console;
 
use App\Providers\{Strings, Schema};

class Console{

	public static function parse($argc, $argv){
		print("Welcome To The Altvel Framework Engineer Console:\n\n");
		if($argc > 1 && 'help' != strtolower($argv[1]) ){
			switch(strtolower($argv[1])){
				case 'app::start':
					self::configureApp($argv[2] ?? 'seven');
					break;
				case 'app::controller':
					if(isset($argv[2]) && ctype_alpha($argv[2])){
						str_ireplace('controller', '', $argv[2]);
						self::generateController($argv[2]);
					}
					break;
				case 'app::model':
					if(isset($argv[2]) && ctype_alpha($argv[2])){
						str_ireplace('model', '', $argv[2]);
						self::generateModel($argv[2]);
					}
					break;
				case 'app::db':
					self::db($argv[2] ?? 'sevenphp');
					echo "{$argv[2]} database has been created", "\n";
					break;
				case 'app::migrate':
					self::migrate();
					echo "Migrations have been registered, generated and run.", "\n";
					break;
				default:
					print("Invalid Syntax. \n\n");
					break;
			}
		}else{
			self::help();
		}
		exit;
	}

	public static function db($db)
	{
		return Schema::db($db);
	}

	public static function migrate()
	{
		return Schema::run();
	}

	public static function generateModel($name){
		$nm = ucfirst($name);
		if (!file_exists(ROOT.DS."app".DS.$nm.".php")) {
			$table = strtolower($name);
			$model = fopen(ROOT.DS."app".DS."{$nm}.php", "w+");
			$vw = "<?php \nnamespace App;\n\nuse App\Providers\Model; \n\nclass {$nm} extends Model{\n\n\tprotected static \$table = '{$table}'; \n\n\tprotected static \$fulltext = [];\n\n}";
			fwrite($model, $vw);
			fclose($model);
			print("{$nm} Model has been generated.\n\n");
		}else{
			print("{$nm} Model already exists.\n\n");
		}
	}

	public static function generateController($name){
		$name = strtolower($name);
		$nm = ucfirst($name);
		$cont = fopen(ROOT.DS."App".DS."Controllers".DS."{$nm}Controller.php", "w+");
		$var = "<?php \nnamespace App\Controllers;\n\nuse App\Providers\Strings;\n\nclass {$nm}Controller extends Controller{\n\n\tpublic function index(){\n\t}\n}";
		fwrite($cont, $var);
		fclose($cont);
		print("{$nm}Controller and Model has been generated.\n\n");
	}

	public static function help(){
		print("To generate secured keys for your Altvel app, use:\n\t");
		print("\"php Engineer App::start {{ APP_NAME }}\" \n\n");
		echo "To generate a controller: \n\t \"php Engineer App::Controller {{ controller_name }}\" \n\n";
		echo "To generate a model: \n\t \"php Engineer App::Model {{ model_name }}\" \n\n";
		echo "To generate a migration: \n\t \"php Engineer App::migrate\" \n\n";
	}

	public static function configureApp($name){
		if (!file_exists(ROOT.DS.'config'.DS.'app.php')) {
			$model = fopen(ROOT.DS.'config'.DS.'app.php', "w+");
			$vw = "<?php \nreturn [
	#App settings
	'APP_NAME' => 'Altvel',
	'APP_KEY' => '',
	'APP_ALG' => '',
	'APP_IV' => '',
	'APP_SALT' => '',
	'APP_DEBUG' => true,
	'APP_URL' => 'http://localhost/altvel',
	'APP_CDN' => 'http://localhost/altvel/cdn',
	'APP_ROOT' => __DIR__.'/..',


	#Mail
	'app_email' => '',
	
	#Sessions & Cookies
	'CURRENT_USER_SESSION_NAME' => '',
	'remember_me' => '',
	'REMEMBER_ME_COOKIE_EXPIRY' => 2592000,
	'redirect' => '',


	#Files, Filesystem and Storage Upload Settings
	'cdn' => __DIR__.'/../public/cdn',
	'view' => __DIR__.'/../public/view',
	'assets' => 'http://localhost/altvel/public/assets',
	'cache' => __DIR__.'/../cache',
	'upload_limit' => 5024768,
	'allowed_files' => [ 
		'jpg' => 'image/jpeg',
		'png' => 'image/png', 
		'jpeg' => 'image/jpeg'
	],


	#Database Migration Settings
	'ENGINE' => [
		'TYPE' => 'SQL',
		'CHARSET' => 'utf8mb4',
		'COLLATE' => 'utf8mb4_unicode_ci',
		'MIGRATIONS' => [ 'users', 'user_sessions', 'contact_us' ],
	],

	#Html Templates
	/*----------------------------------------------------------------------------------------------|
	|								LARAFELL NAVIGATION BAR											|
	|-----------------------------------------------------------------------------------------------|
	|	this helps in setting the menu bar for guest users and loggged in users based on the array 	|
	|	associative arrays can be used for menus with dropdown... 									| 
	-----------------------------------------------------------------------------------------------*/

	'USER_NAVBAR' => ['Home' => 'home', 'Search' => 'search', 'Logout' => 'logout'],
	'GUEST_NAVBAR' => ['Login' => 'login', 'Register' => 'register', 'About' => 'about'],

	'controllers' => [
		'AuthController' => ['login', 'register', 'forgot_password', 'activate', 'about', 'logout'],
		'ErrorsController' => ['_404', '_405', 'bad', 'denied', 'unknown'],
		'RESTRICTED' => [
			/*----------------------------------------------------------------------
			| Controllers that requires login must reside in this restricted array.
			----------------------------------------------------------------------*/
			'HomeController' => [],
			'SearchController' => []
		],
	],
	'DEFAULT_CONTROLLER' => 'AuthController',


	'services' => [
		/*
		|-------------------------------------------------------------------------------------|
		|Register all api services your application makes use of in the form of: name => url  |
		|-------------------------------------------------------------------------------------|
		*/
	],
];";
			
			fwrite($model, $vw);
			fclose($model);
		}
		$config = ROOT.DS.'config'.DS.'app.php';
		self::configureTITLE($config, $name);
		self::configureFOLDER($config, $name);
		self::configureSALT($config);

		print("Environment Security Configurations have been successfully set up.\n");
		print("Please rename your root folder (i.e. your current folder) to {$name}. \n");
		print("You may still have to manually setup some configurations for a production\nserver in the config/app.php file of your application.\n");
		exit();
	}

	public static function configureTITLE($file, $title){
		file_put_contents($file, implode('', array_map(function($data) use ($title){
			return (strstr($data, "'APP_NAME'")) ? "\t'APP_NAME' => '{$title}',\n" : $data;
		}, file($file))));
	}

	public static function configureFOLDER($file, $brand){
		file_put_contents($file, implode('', array_map(function($data) use ($brand){
			return (strstr($data, "'APP_ROOT'")) ? "\t'APP_ROOT' => __DIR__.'/..',\n" : $data;
		}, file($file))));
	}
	
	public static function configureSALT($file){
		$ciphers = openssl_get_cipher_methods();
		$ciphers = array_filter( $ciphers, function($n) { return stripos($n,"ecb")===FALSE; } );
		$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"des")===FALSE; } );
		$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"rc2")===FALSE; } );
		$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"rc4")===FALSE; } );
		$ciphers = array_filter( $ciphers, function($c) { return stripos($c,"md5")===FALSE; } );
		$ciphers = array_values($ciphers); $limit = count($ciphers)-1;
		$cipher = $ciphers[ random_int(0, $limit )];

		file_put_contents($file, implode('', array_map(function($data) use ($cipher){
			$iv = base64_encode( openssl_random_pseudo_bytes( openssl_cipher_iv_length($cipher) ));
			return (strstr($data, "'APP_IV'")) ? "\t'APP_IV' => '{$iv}',\n" : $data;
		}, file($file))));
		
		file_put_contents($file, implode('', array_map(function($data) use ($cipher){
			if ( strstr($data, "'JWT' => 'HS256'") ) {
				$randstring = Strings::fixed_length_token(random_int(64, 256)).Strings::rand_token();
				return (strstr($data, "'PUBLIC_KEY'")) ? "\t'PUBLIC_KEY' => '{$randstring}',\n" : $data;
			}
		}, file($file))));

		file_put_contents($file, implode('', array_map(function($data) use ($cipher){
			if ( strstr($data, "'JWT' => 'HS256'") ) {
				$randstring = Strings::fixed_length_token(random_int(64, 256)).Strings::rand_token();
				return (strstr($data, "'PRIVATE_KEY'")) ? "\t'PRIVATE_KEY' => '{$randstring}',\n" : $data;
			}
		}, file($file))));

		file_put_contents($file, implode('', array_map(function($data) use ($cipher){
			return (strstr($data, "'APP_ALG'")) ? "\t'APP_ALG' => '{$cipher}',\n" : $data;
		}, file($file))));

		file_put_contents($file, implode('', array_map(function($data){
			$const = Strings::Rand(32);
			return (strstr($data, "'APP_SALT'")) ? "\t'APP_SALT' => '{$const}',\n" : $data;
		}, file($file))));
	}
}