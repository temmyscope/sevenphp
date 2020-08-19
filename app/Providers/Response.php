<?php
namespace App\Providers;

class Response
{
    public static function send($data, $status_code = 200, $cache = false)
    {
        if ($cache === true) {
            header("Cache-Control: no-transform,public,max-age=7200,s-maxage=7200");    // set the header to make sure cache is forced
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status_code);
        echo json_encode($data, JSON_PRETTY_PRINT);
    }
}
