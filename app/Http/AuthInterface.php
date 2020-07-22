<?php
namespace App\Http;

Interface AuthInterface{

    public static function isLoggedIn(): bool;

    public static function getUserId(): int;

}