<?php
namespace App;

use App\Providers\Model;

class Users extends Model
{

	protected static $table = 'users';

	protected static $fulltext = [];

}