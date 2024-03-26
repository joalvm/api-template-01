<?php

namespace App\DataObjects\Repositories;

use App\Enums\Gender;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdatePersonData extends Data
{
    public function __construct(
        public string|Optional $names,
        #[MapName('last_names')]
        public string|Optional $lastNames,
        public Gender|Optional $gender,
        #[MapName('document_type_id')]
        public int|Optional $documentTypeId,
        #[MapName('id_document')]
        public string|Optional $idDocument,
        public string|Optional|null $email,
    ) {
    }
}
