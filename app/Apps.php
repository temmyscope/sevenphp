<?php
namespace App;

use App\Providers\{Model};

class Apps extends Model
{

	protected static $table = 'apps';

	protected static $fulltext = [];

	protected static $fetchable = [ 'id', 'name', 'account_balance', 'is_verified', 'created_at' ];

}