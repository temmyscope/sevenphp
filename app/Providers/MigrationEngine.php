<?php
namespace App\Providers;

class MigrationEngine extends Schema{

	public function migrate()
	{
		return [
			
		];
		//constraints: a table can only have one primary key and it will be autogenerated
	}

	public function populate()
	{
		return [

		];
	}

	public function drop($table)
	{
		return [ 'user', 'user_sessions' ]; //drop tables
	}

}