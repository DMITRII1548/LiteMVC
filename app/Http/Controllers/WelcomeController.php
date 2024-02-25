<?php 

namespace App\Http\Controllers;

use App\RMVC\View\View;

class WelcomeController 
{
    public function index(): string
    {
        return View::view('welcome');
    }
}