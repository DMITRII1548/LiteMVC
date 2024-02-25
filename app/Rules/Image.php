<?php

namespace App\Rules;

class Image
{
    public function validate(string $attribute, mixed $value): void
    {
        if (
            !(
                $value['type'] === 'image/png'
                || $value['type'] === 'image/jpeg'
                || $value['type'] === 'image/jpg'                
            )
        ) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                $_SESSION['errors']['validation'][$attribute] = "attribute $attribute must be image type";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                header('HTTP/1.0 322 Forbidden');
            }

            die();
        }
    }
}
