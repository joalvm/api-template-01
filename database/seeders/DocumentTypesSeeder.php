<?php

namespace Database\Seeders;

use App\Enums\CharType;
use App\Enums\LengthType;
use App\Models\DocumentType;
use Illuminate\Database\Seeder;

class DocumentTypesSeeder extends Seeder
{
    public const DEFAULT_DOCUMENT_TYPE = 1;

    public static $data = [
        [
            'id' => 1,
            'name' => 'LIBRETA ELECTORAL O DNI',
            'abbr' => 'L.E / DNI',
            'length_type' => LengthType::EXACT,
            'length' => 8,
            'char_type' => CharType::NUMERIC,
        ],
        [
            'id' => 2,
            'name' => 'CARNET DE EXTRANJERIA',
            'abbr' => 'CARNET EXT.',
            'length_type' => LengthType::MAX,
            'length' => 12,
            'char_type' => CharType::ALPHA_NUMERIC,
        ],
        [
            'id' => 3,
            'name' => 'REG. UNICO DE CONTRIBUYENTES',
            'abbr' => 'RUC',
            'length_type' => LengthType::EXACT,
            'length' => 11,
            'char_type' => CharType::NUMERIC,
        ],
        [
            'id' => 4,
            'name' => 'PASAPORTE',
            'abbr' => 'PASAPORTE',
            'length_type' => LengthType::MAX,
            'length' => 12,
            'char_type' => CharType::ALPHA_NUMERIC,
        ],
        [
            'id' => 5,
            'name' => 'PART. DE NACIMIENTO-IDENTIDAD',
            'abbr' => 'P. NAC.',
            'length_type' => LengthType::MAX,
            'length' => 15,
            'char_type' => CharType::ALPHA_NUMERIC,
        ],
        [
            'id' => 6,
            'name' => 'OTROS',
            'abbr' => 'OTROS',
            'length_type' => LengthType::MAX,
            'length' => 15,
            'char_type' => CharType::ALPHA_NUMERIC,
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach (self::$data as $data) {
            DocumentType::query()->insert($data);
        }
    }
}
