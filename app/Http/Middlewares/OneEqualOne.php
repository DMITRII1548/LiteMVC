<?php 

namespace App\Http\Middlewares;

class OneEqualOne
{
    public function handle(): void
    {
        if (1 !== 1) {
            header('HTTP/1.1 403 Forbidden');
            
            die();
        }
    }
}