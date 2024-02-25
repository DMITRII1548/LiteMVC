<?php

namespace App\RMVC\Middleware;

use App\Http\Kelner;

class Middleware
{
    public static function run(string $middlewareName): void
    {
        $kelner = new Kelner();
 
        if (isset($kelner->middlewares[$middlewareName])) {
            (new $kelner->middlewares[$middlewareName])->handle();
            return;
        } else {
            trigger_error("Middleware $middlewareName does not exit");
        }
    }
}
