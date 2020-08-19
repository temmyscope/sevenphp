<?php

function request()
{
	return new class(){
		public function __construct()
		{
			@$this->data = json_decode( file_get_contents('php://input'), true);
			@$this->token  = $_SERVER['HTTP_AUTHORIZATION'];
		}
		public function get( string $var, bool $sanitized = true)
		{
			if($sanitized === false){
				return $this->data[$var] ?? $_REQUEST[$var];
			}
			return htmlentities( $this->data[$var] ?? $_REQUEST[$var], ENT_QUOTES, 'UTF-8');
		}
		public function set($var, $value)
		{
			$this->data[$var] = $value;
		}
		public function getFile($var)
		{
			return $_FILES[$var] ?? null;
		}
		public function getToken()
		{
			return $this->token;
		}
		public function has(string $var): bool
		{
			return (isset($this->data[$var]) || isset($_REQUEST[$var]) );
		}
		public function all()
		{
			return (array)$this->data ?? $_REQUEST;
		}
	};
}

public function response($data, $code, $format='json')
{
	switch ($format) {
		case 'json':
			header('Content-Type: application/json; charset=utf-8');
        	http_response_code($code);
			return echo json_encode($data, JSON_PRETTY_PRINT);
		case 'xml':
			break;
		case 'html':
			break;
		default:
			break;
	}
}

function curl($url)
{
	return new class($url){
        protected $_curl = [
            'url' => '',
			'data' => [],
			'headers' => [],
            'time_out' => 200,
            'cookie_file' => '',
            'cookie_jar' => '',
            'method' => 'GET',
            'ret' => true,
        ];
        protected $_result, $_errors;
		function __construct($url){
			$this->_curl['url'] = filter_var($url, FILTER_SANITIZE_URL);
        }
		public function setData(array $postdata){
            $this->_curl['data'] = json_encode($postdata);
			return $this;
		}
		public function setHeaders($headers)
		{
			$this->_curl['headers'] = $headers;
			return $this;	
		}
		public function setHeader($headers)
		{
			return $this->setHeaders($headers);
		}
		public function setSession($cookiefile){
            $this->_curl['cookie_file'] = $cookiefile;
			return $this;
        }
		public function saveSession($cookiefile){
            $this->_curl['cookie_jar'] = $cookiefile;
			return $this;
        }
		public function setMethod(string $method){
            $this->_curl['method'] = strtoupper($method);
			return $this;
        }
		public function isReturnable(bool $val = true){
            $this->_curl['ret'] = $val;
			return $this;
        }
		public function setTimeOut($time = 200){
            $this->_curl['time_out'] = $time;
			return $this;
        }
		public function send(){
			array_push($this->_curl['headers'], 'Content-Type: application/json');
            $ch = curl_init($this->_curl['url']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($this->_curl['method']) );
            if ( !empty($this->_curl['data']) ) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_curl['data']);
            }
            if (!empty($this->_curl['cookie_jar']) && !empty($this->_curl['cookie_file']) ) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_curl['cookie_jar'] );
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_curl['cookie_file'] );
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->_curl['ret']);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->_curl['time_out'] );
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->_curl['time_out'] );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_curl['headers']);
			$this->_result = curl_exec($ch);
            $this->_errors = curl_error($ch);
			curl_close($ch);
			if ( $this->_errors ) {
				return false;
			} else {
				return $this->_result;
			}
        }
		public function result()
		{
			return $this->_result;
        }
		public function errors()
		{
			return $this->_errors;
        }
	};
}

function app($config = __DIR__.'/../../config/app.php')
{
	return new class($config){
		public function __construct($config){
			$this->config = require $config;
		}
		public function __call($method, $args){
			return $this->config[ strtolower($method) ] ?? null;
		}
		public function get(string $var)
		{
			return $this->config[$var] ?? null;
		}

		public function all()
		{
			return $this->config;
		}
	};
}

function sanitize($dirty){
	$clean_input = [];
    if(is_array($dirty)){
        foreach ($dirty as $k => $v) {
            $clean_input[$k] = htmlentities($v, ENT_QUOTES, 'UTF-8');
        }
    } else {
        $clean_input = htmlentities($dirty, ENT_QUOTES, 'UTF-8');
    }
    return $clean_input;
}

function dnd($var){
	echo "<pre>";
		var_dump($var);
	echo "<pre>";
	die();
}

function app_url(): string{
	return app()->get('APP_URL');
}

function get($var = ''){
	if(!empty($var)){
		return (isset($_GET[$var])) ? sanitize($_GET[$var]) : null;	
	}else{
		return (!empty($_GET)) ? (object) sanitize($_GET) : null;
	}
}

function post($var = ''){
	if(!empty($var)){
		return (isset($_POST[$var])) ? sanitize($_POST[$var]) : null;
	}else{
		return (!empty($_POST)) ? (object) sanitize($_POST) : null;
	}
}

function destroy_request(): bool{
	$_GET = $_POST = $_REQUEST = $_FILES = [];
	return true;
}

/*--------------------------------------------------------------
|	$arr1 = [
|			'function'=> 'strpos',
|			'parameters'=> ['home.php', '.']
|	]; 
	$arr2 = [
|			'function'=> 'strstr',
|			'parameters'=> ['home.php', '.']
|	];
|	speed_cmp($arr1, $arr2);
----------------------------------------------------------------*/
function speed_cmp(...$args){
	if(count($args) > 1){
		foreach($args as $key => $value){
			$time_start= microtime(true);
			$mem_start = memory_get_usage(true);
			for ($i=0; $i <= 10000; $i++) { 
				call_user_func_array($args[$key]['function'], $args[$key]['parameters']);
			}
			$mem_end = memory_get_usage(true);
			$time_end= microtime(true);
			$time_elapsed= $time_end - $time_start;
			$memory_used = $mem_end - $mem_start;
			echo "<pre>";
			echo "Time elapsed for testcase <b>{$key}</b> is {$time_elapsed}";
			echo "Memory used for testcase <b>{$key}</b> is {$memory_used}";
			echo "<pre>";
		}
	}else{
		throw new Exception("Testcases must be atleast 2", 1);
	}
}