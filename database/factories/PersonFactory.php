<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\DocumentType;
use App\Models\Person;
use Faker\Generator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    protected $model = Person::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fake = fake('es_PE');
        $gender = Gender::random();

        $documentType = DocumentType::factory()->create();

        return [
            'names' => $this->getName($gender, $fake),
            'last_names' => $fake->lastName(),
            'gender' => $gender,
            'document_type_id' => $documentType->id,
            'id_document' => $fake->unique()->dni(),
            'email' => $fake->email(),
        ];
    }

    private function getName(Gender $gender, Generator $fake)
    {
        if (Gender::FEMALE === $gender) {
            // Solo caracteres alfabéticos y espacios
            return preg_replace('/[^a-zA-Z ]/', '', $fake->firstNameFemale());
        }

        return preg_replace('/[^a-zA-Z ]/', '', $fake->firstNameMale());
    }
}
