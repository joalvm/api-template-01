<?php

namespace App\Rules\Pgsql;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

class IntegerPositiveRule implements ValidationRule, ValidatorAwareRule
{
    public const MAX_SMALL_INT = IntegerRule::MAX_SMALL_INT;
    public const MAX_INT = IntegerRule::MAX_INT;
    public const MAX_BIG_INT = IntegerRule::MAX_BIG_INT;

    /**
     * The validator instance.
     *
     * @var Validator
     */
    protected $validator;

    public function __construct(private int $bits = 4)
    {
        if (!in_array($this->bits, [2, 4, 8])) {
            throw new \InvalidArgumentException('The bit must be 2, 4 or 8');
        }
    }

    /**
     * Set the current validator.
     */
    public function setValidator(Validator $validator): static
    {
        $this->validator = $validator;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (is_null($value) and $this->validator->hasRule($attribute, 'nullable')) {
            return;
        }

        if (!is_int($value)) {
            $fail(Lang::get('validation.integer', ['attribute' => $attribute]));
        }

        if ($value < 1 or $value > $this->max()) {
            $fail(Lang::get('validation.between.numeric', [
                'attribute' => $attribute,
                'min' => 1,
                'max' => $this->max(),
            ]));
        }
    }

    public function max(): int
    {
        return match ($this->bits) {
            2 => self::MAX_SMALL_INT,
            4 => self::MAX_INT,
            8 => self::MAX_BIG_INT,
        };
    }
}
