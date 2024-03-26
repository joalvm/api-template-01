<?php

namespace App\DataObjects\Repositories\Ubigeo;

use Spatie\LaravelData\Data;

class CreateDepartmentData extends Data
{
    public function __construct(
        public string $name,
        public string $code,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
    }
}
