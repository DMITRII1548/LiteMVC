<?php 

namespace App\RMVC\Request;

abstract class FormRequest extends Request
{
    abstract protected function rules(): array;

    public function validated(): array
    {
        return $this->validate($this->rules());
    }
}