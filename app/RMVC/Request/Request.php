<?php 

namespace App\RMVC\Request;

use App\Http\Kelner;

class Request
{
    private array $data;

    public function __construct()
    {
        unset($_POST['csrf']);
        $this->data = array_merge($_FILES, $_POST);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function validate(array $rules): array
    {
        if ($rules) {
            (new Validator())->validate($rules, $this->data);
        }

        return $this->data;
    }
}