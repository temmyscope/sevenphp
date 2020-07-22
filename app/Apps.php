<?php
namespace App;

use App\Providers\{Model, Schema};

class Apps extends Model
{

	protected static $table = 'apps';

	protected static $fulltext = [];

	protected static $fetchable = [ 'id', 'name', 'account_balance', 'is_verified', 'created_at' ];

	public function migrate(Schema $schema)
	{
		return [
			'id' => $schema->integer($max_length=10)->is_null(true)->primary() || $schema->integer($max_length=10, $null = false, $key='primary'),
			'name' => $schema->string($max_length=1125)->is_null(false) || $schema->string($max_length=125, $null=true),
			'account_balance' => $schema->float($max_length=10)->is_null(false) || $schema->string($max_length=125, $null=true),
			'is_verified' => $schema->boolean()->default() || $schema->boolean($default=) || $schema->oneOf(['true', 'false'])->default('false'),
			'created_at' => $schema->time()->auto_generate(true) || $schema->time($auto_generate=true)
		];
	}

	public function populate()
	{
		return [ [ 'name' => , 'account_balance' => , 'is_verified' => ],  ];
	}

}