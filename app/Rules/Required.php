<?php 

namespace App\Rules;

class Required
{
    public function validate(string $attribute, mixed $value): void
    {
        if (!$value) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $_SESSION['errors']['validation'][$attribute] = "attribute $attribute must be required";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header('HTTP/1.0 322 Forbidden');
            }

            die();
        }
    }
}