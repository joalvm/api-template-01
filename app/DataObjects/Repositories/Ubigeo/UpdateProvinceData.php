<?php

namespace App\DataObjects\Repositories\Ubigeo;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class UpdateProvinceData extends Data
{
    public function __construct(
        #[MapName('department_id')]
        public int|Optional $departmentId,
        public string|Optional $name,
        public string|Optional $code,
        public float|Optional|null $latitude = null,
        public float|Optional|null $longitude = null,
    ) {
    }
}
