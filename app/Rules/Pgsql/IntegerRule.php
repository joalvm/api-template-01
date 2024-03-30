<?php

namespace App\Rules\Pgsql;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Validation\Validator;

class IntegerRule implements ValidationRule, ValidatorAwareRule
{
    public const MIN_SMALL_INT = -32768;
    public const MAX_SMALL_INT = 32767;
    public const MIN_INT = -2147483648;
    public const MAX_INT = 2147483647;
    public const MIN_BIG_INT = -9223372036854775808;
    public const MAX_BIG_INT = 9223372036854775807;

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
            $fail($attribute, 'validation.integer');
        }

        if ($value < $this->min() || $value > $this->max()) {
            $fail($attribute, 'validation.integer_between', [
                'min' => $this->min(),
                'max' => $this->max(),
            ]);
        }
    }

    public function min(): int
    {
        return match ($this->bits) {
            2 => self::MIN_SMALL_INT,
            4 => self::MIN_INT,
            8 => self::MIN_BIG_INT,
        };
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
