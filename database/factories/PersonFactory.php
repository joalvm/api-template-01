<?php

namespace Database\Factories;

use App\Enums\CharType;
use App\Enums\Gender;
use App\Enums\LengthType;
use App\Models\Person;
use Database\Seeders\DocumentTypesSeeder;
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
        $document = $this->generateDocument($fake);

        return [
            'names' => $this->getName($gender, $fake),
            'last_names' => $fake->lastName(),
            'gender' => $gender,
            'document_type_id' => $document['document_type_id'],
            'id_document' => $document['id_document'],
            'email' => $fake->email(),
        ];
    }

    private function generateDocument(Generator $fake)
    {
        $documentType = $fake->randomElements(DocumentTypesSeeder::$data)[0];

        if (6 === $documentType['id']) {
            return $this->generateDocument($fake);
        }

        if (CharType::NUMERIC === $documentType['char_type']) {
            if (LengthType::EXACT === $documentType['length_type']) {
                return [
                    'document_type_id' => $documentType['id'],
                    'id_document' => $fake->numerify(
                        str_repeat('#', $documentType['length'])
                    ),
                ];
            }

            return [
                'document_type_id' => $documentType['id'],
                'id_document' => $fake->numerify(
                    str_repeat(
                        '#',
                        $fake->numberBetween(
                            $documentType['length'] - 2,
                            $documentType['length']
                        )
                    )
                ),
            ];
        }

        if (LengthType::EXACT === $documentType['length_type']) {
            return [
                'document_type_id' => $documentType['id'],
                'id_document' => $fake->bothify(
                    str_repeat('#', $documentType['length'])
                ),
            ];
        }

        return [
            'document_type_id' => $documentType['id'],
            'id_document' => $fake->bothify(
                str_repeat(
                    '#',
                    $fake->numberBetween(
                        $documentType['length'] - 2,
                        $documentType['length']
                    )
                )
            ),
        ];
    }

    private function getName(Gender $gender, Generator $fake)
    {
        if (Gender::FEMALE === $gender) {
            return $fake->firstNameFemale();
        }

        return $fake->firstNameMale();
    }
}
