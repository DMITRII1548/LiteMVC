<?php 

namespace App\Http;

use App\Http\Middlewares\OneEqualOne;
use App\Rules\Image;
use App\Rules\IsFloat;
use App\Rules\IsInt;
use App\Rules\IsString;
use App\Rules\Required;

class Kelner
{
    // Register middlewares
    public array $middlewares = [
        // 'middleware_name' => 'class'
        'one.is.one' => OneEqualOne::class
    ];

    // Register validation rules 
    public array $rules = [
        'required' => Required::class,
        'int' => IsInt::class,
        'float' => IsFloat::class,
        'string' => IsString::class,
        'image' => Image::class
    ];
}

