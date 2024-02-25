<?php

namespace App\Rules;

class IsString
{
    public function validate(string $attribute, mixed $value): void
    {
        if (!is_string($value)) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $_SESSION['errors']['validation'][$attribute] = "attribute $attribute must be string type";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                header('HTTP/1.0 322 Forbidden');
            }

            die();
        }
    }
}
