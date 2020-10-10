<?php
return [
	#App settings
	'APP_NAME' => 'Qlover',
	'APP_IV' => '',
	'APP_ALG' => '',
	'APP_SALT' => '',
	'APP_DEBUG' => true,
	'APP_URL' => 'https://qlover.org',
	'APP_CDN' => 'https://qlover.org/cdn',
	'APP_EMAIL' => '',
	'APP_ROOT' => __DIR__.'/..',

	#Security & API
	'PRIVATE_KEY' => "",
	'PUBLIC_KEY' => "",
	'JWT' => 'HS256',
	"ISSUER" => "",
	"AUDIENCE" => "",


	'activate_url' => 'http://qlover.org/activate/',

	#File and Storage Upload Settings
	'cdn' => __DIR__.'/../cdn',
	'cache' => __DIR__.'/../cache',
	
	#PWA Firebase Configurations
	'firebase_token' => '',
];