<?php

namespace App\DataObjects\Repositories\Ubigeo;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

class CreateDistrictData extends Data
{
    public function __construct(
        #[MapName('province_id')]
        public int $provinceId,
        public string $name,
        public string $code,
        public ?float $latitude = null,
        public ?float $longitude = null,
    ) {
    }
}
