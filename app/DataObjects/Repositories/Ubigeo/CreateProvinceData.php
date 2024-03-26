<?php

namespace App\DataObjects\Repositories\Ubigeo;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class CreateProvinceData extends Data
{
    public function __construct(
        #[MapName('department_id')]
        public int $departmentId,
        public string $name,
        public string $code,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
    }
}
