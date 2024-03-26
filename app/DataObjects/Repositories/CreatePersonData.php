<?php

namespace App\DataObjects\Repositories;

use App\Enums\Gender;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class CreatePersonData extends Data
{
    public function __construct(
        public string $names,
        #[MapName('last_names')]
        public string $lastNames,
        public Gender $gender,
        #[MapName('document_type_id')]
        public int $documentTypeId,
        #[MapName('id_document')]
        public string $idDocument,
        public ?string $email,
    ) {
    }
}
