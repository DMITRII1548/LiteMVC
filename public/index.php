<?php

use App\RMVC\App;

session_start();

require './../vendor/autoload.php';
require './../routes/web.php';
require './../app/RMVC/helpers.php';

App::run();

