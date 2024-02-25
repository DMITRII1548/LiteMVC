<?php 

namespace App\RMVC\Request;

use App\Http\Kelner;

class Validator
{
    private Kelner $kelner;
    private array $data;

    public function __construct()
    {
        $this->kelner = new Kelner();
    }

    public function validate(array $rules, array $data): void
    {
        $this->data = $data;

        foreach ($rules as $attribute => $ruleNames) {
            $ruleNames = $this->parseRules($ruleNames);

            if ($ruleNames) {
                $this->runRules($ruleNames, $attribute);
            }
        }
    }

    private function parseRules(string $ruleNames): array|false
    {
        return explode('|', $ruleNames);
    }

    private function runRules(array $rules, string $attribute): void
    {
        foreach ($rules as $rule) {
            if ($rule === '') {
                return;
            }
            
            if (!isset($this->kelner->rules[$rule])) {
                trigger_error("Rule $rule does not exist");
                die();
            }

            (new $this->kelner->rules[$rule])->validate($attribute, $this->data[$attribute]);
        }
    }   
}