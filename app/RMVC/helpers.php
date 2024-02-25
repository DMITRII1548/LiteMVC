<?php 

function csrf(): string 
{    
    $token = md5(random_int(1, 99999) . microtime(true));

    $_SESSION['csrf'] = $token;

    return $token;
}

function method(string $method): void
{
    echo '<input type="hidden" name="_method" value="' . $method . '">';
}