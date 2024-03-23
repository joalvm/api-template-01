<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class AlphaSpaceRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)*$/', strval($value))) {
            $fail('validation.alpha_space', ['attribute' => $attribute]);
        }
    }
}
