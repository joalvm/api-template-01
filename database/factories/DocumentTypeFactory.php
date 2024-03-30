<?php

namespace Database\Factories;

use App\Enums\CharType;
use App\Enums\LengthType;
use App\Models\DocumentType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentTypeFactory extends Factory
{
    protected $model = DocumentType::class;

    public function definition()
    {
        $fake = fake('ES_PE');

        return [
            'name' => $fake->text(20),
            'abbr' => $fake->asciify('******'),
            'length_type' => $fake->randomElement(LengthType::values()),
            'length' => $fake->numberBetween(1, 100),
            'char_type' => $fake->randomElement(CharType::values()),
        ];
    }
}
