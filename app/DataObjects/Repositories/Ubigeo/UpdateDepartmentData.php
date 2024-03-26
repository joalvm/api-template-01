<?php

namespace App\DataObjects\Repositories\Ubigeo;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateDepartmentData extends Data
{
    public function __construct(
        public string|Optional $name,
        public string|Optional $code,
        public float|Optional|null $latitude = null,
        public float|Optional|null $longitude = null,
    ) {
    }
}
