<?php
namespace App\Providers;

use Seven\Vars\Validation;

class MigrationEngine{

	public static function alter(string $table)
	{
		$engine = app()->get('ENGINE');
		$migration = require __DIR__.'/../../migrations/alter.php';
		$table = ($table === 'all') ? $migration : [ $migration[$table] ];
		if ($engine['TYPE'] === 'SQL') {
			return self::migrateSQL($table, $engine['CHARSET']);
		}
	}

	public static function drop(string $table)
	{
		$engine = app()->get('ENGINE');
		$migration = require __DIR__.'/../../migrations/drop.php';
		$table = ($table === 'all') ? $migration : [ $migration[$table] ];
		if ($engine['TYPE'] === 'SQL') {
			return self::migrateSQL($table, $engine['CHARSET']);
		}
	}


	public static function run(string $table)
	{
		$engine = app()->get('ENGINE');
		$migration = require __DIR__.'/../../migrations/create.php';
		$table = ($table === 'all') ? $migration : [ $migration[$table] ];
		if ($engine['TYPE'] === 'SQL') {
			return self::creationSQL($table, $engine['CHARSET'], $engine['COLLATION']);
		}
	}

	private static function creationSQL(array $arr, string $charset, string $collation)
	{
		$sql = "";
		foreach($arr as $table => $columns){
			$sql .= "CREATE TABLE IF NOT EXISTS {$table} (";
			foreach ($columns as $column) {
				if ( isset($column['type']) ) {
					switch ($column['type']) {
						case 'int':
							$sql .= "{$columns} INT";
							break;
						default:
							break;
					}
				}
				if (isset($column['len'])) {
					if ($column['len']) {
						# code...
					}
				}
				if (isset($column['nullable'])) {
					
				}
				if (isset($column['comment'])) {
					
				}
				if (isset($column['type'])) {
					
				}
				if isset($column['indexer'])) {
					
				}
			}
			$sql .= ") "
		}
	}

}