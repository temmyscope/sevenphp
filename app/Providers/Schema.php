<?php
namespace App\Providers;

class Schema{

	public function __migrations()
	{
		$migrations = app()->get('ENGINE')['MIGRATIONS'];
		$arr = [];
		foreach ($migrations as $key) {
			$arr[] = (new $key)->migrate($this);
		}
	}

	public function __migration($model)
	{
		$mig = (new 'App\\'.$model)->migrate($this);
	}

	public function type($value='', int $max_length)
	{
		# code...
	}

	public function integer( int $max_length)
	{
		# code...
	}

	public function float( int $max_length)
	{
		# code...
	}

	public function string( int $max_length)
	{
		# code...
	}

	public function oneOf(array $options)
	{
		
	}

	public function boolean()
	{
		# code...
	}

	public function date()
	{
		# code...
	}

	public function time()
	{
		
	}

	public function year()
	{
		
	}

	public function timestamp()
	{
		
	}

	public function comment($value='')
	{
		# code...
	}

	public function is_null()
	{
		# code...
	}

	public function primary()
	{
		# code...
	}

	public function default($value)
	{
		# code...
	}

	public function auto_generate(true)
	{
		# code...
	}

	public function key('')
	{
		# code...
	}

	public function index('')
	{
		# code...
	}
}