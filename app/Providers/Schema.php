<?php
namespace App\Providers;

use {MigrationEngine, Model};

use \PDO;
use \PDOException;

class Schema{

	public function __construct()
	{
		$this->mige = new MigrationEngine();
		$this->engine = app()->get('ENGINE');
		$this->migrable = $this->engine['MIGRATIONS'];
	}

	public static function init()
	{
		return new self();
	}

	public function run()
	{	
		try {
			$db = str_replace("pdo_", '', Model::$driver); $server = Model::$host; $dbname = Model::$dbname;
	    	$conn = new PDO("$db:host=$server;dbname=$dbname", Model::$user, Model::$password);
	    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    	$drop = $this->mige->drop();
	    	if( !empty($drop) ){
	    		$sql = "";
		    	foreach ($drop as $key => $value) {
		    		$sql .= "DROP TABLE {$value}; ";
		    	}
		    	$conn->exec($sql);
		    	echo "Tables have been dropped";
	    	}

	    	$conn->exec($this->parser());
	    	echo "Migration created successfully";

	    	$this->InsertionIfExists();

	    }catch(PDOException $e){
		    echo $sql . "<br>" . $e->getMessage();
	    }
	}

	public function InsertionIfExists()
	{
		$data = $this->mige->populate();
		if (!empty($data)) {
			foreach ($data as $table => $entry) {
				Model::setTable($table)->insert($entry);
			}
		}
		echo "Insertions Successful.";
	}

	public function parser()
	{
		$mig = $this->mige->migrate($this);
		$chs = $this->engine['CHARSET']; $col = $this->engine['COLLATE'];
		$queue = ["ALTER TABLE :table ADD PRIMARY KEY (id),"];
		$_sql = "";
		if( !empty($mig) ){
			foreach ($mig as $table => $columns) {
				if (in_array($table, $this->migrable)) {
					$_sql .= "CREATE TABLE `{$table}` (
					id int(10) NOT NULL AUTO_INCREMENT,
					";
					foreach ($columns as $key => $value) {
						$_sql .= str_replace(":column", $key, $value[0]);
						if (!empty($value[1])) {
							$queue[] = str_replace(":column", $key, str_replace(":table", $table, $value[1]) );
						}
					}
					foreach ($queue as $key => $value) {
						$_sql .= str_replace(':table', $table, $value);
					}
					$_sql .= (!empty($chs) && !empty($col) ) ? ") CHARSET={$chs} COLLATE $col; 
					": "); 
					";
				}
			}
		}
		return $_sql;
	}

	public function integer($max_length=10)
	{
		$t = "";
		return [":column int({$max_length}) NOT NULL,", $t];
	}
	public function double($max_length=10)
	{
		return [ ":column double,", ""];
	}
	public function float($max_length=10)
	{
		return [ ":column float({$max_length}),", ""];
	}
	public function string($max_length=10, $null = false, $key='primary')
	{
		$null = ($null === false) ? "NOT NULL" : "NULL";
		$t = "";
		if(!empty($key)){
			$key = strtolower($key);
			if ( $key === 'unique' ){
				$t = "ALTER TABLE :table ADD UNIQUE :column (:column),";
			}elseif ( $key === 'fulltext' ){
				$t = "ALTER TABLE :table ADD FULLTEXT KEY :column (:column),";
			} elseif ( $key === 'index' ){
				$t = "CREATE INDEX :column ON :table (:column),";
			}
		}
		if ($max_length > 63000) {
			$type =  "text";
		}elseif ($max_length <= 63000) {
			$type = "var_char({$max_length})";
		}elseif ($max_length < 18) {
			$type =  "char({$max_length})" ;
		}
		return [ ":column {$type} {$null}", $t ];
	}
	public function oneOf(array $options, $default)
	{
		$options  = implode(', ', $options);
		return [ ":column enum({$options}) NOT NULL DEFAULT '{$default}',", ""];
	}
	public function datetime()
	{
		return [ ":column enum({$options}) NOT NULL DEFAULT CURRENT_TIMESTAMP,", ""];
	}
	public function foreign_key($table, $column)
	{
		return ["", "ALTER TABLE :table ADD FOREIGN KEY (:column) REFERENCES {$table}({$column}),"];
	}

}