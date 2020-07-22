<?php
namespace App;

use App\Providers\Model;
use App\Http\AuthInterface;
use App\Http\{ StateLess };

class Auth extends Model Implements AuthInterface{

    protected static $table = 'users';
    protected static $fulltext = [];
    
    use StateLess;
}