<?php

namespace App\DataObjects\Repositories;

use App\Enums\CharType;
use App\Enums\LengthType;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class CreateDocumentTypeData extends Data
{
    public function __construct(
        public string $name,
        public string $abbr,
        #[MapName('length_type')]
        public LengthType $lengthType,
        public int $length,
        #[MapName('char_type')]
        public CharType $charType,
    ) {
    }
}
