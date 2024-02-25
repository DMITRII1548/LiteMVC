<?php

use App\Http\Controllers\WelcomeController;
use App\RMVC\Route\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome')->middleware('one.is.one');