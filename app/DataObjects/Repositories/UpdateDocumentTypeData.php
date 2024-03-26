<?php

namespace App\DataObjects\Repositories;

use App\Enums\CharType;
use App\Enums\LengthType;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateDocumentTypeData extends Data
{
    public function __construct(
        public string|Optional $name,
        public string|Optional $abbr,
        #[MapName('length_type')]
        public LengthType|Optional $lengthType,
        public int|Optional $length,
        #[MapName('char_type')]
        public CharType|Optional $charType,
    ) {
    }
}
