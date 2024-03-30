<?php

namespace Tests\Feature\Http\Controllers\Ubigeo;

use App\Models\Ubigeo\Province;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('ubigeo')]
class ProvincesControllerTest extends TestCase
{
    public function test_index_province_when_code_filter_is_applied_then_status_ok(): void
    {
        $collection = Province::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (Province $province) => $province->code === $model->code
        )->count();

        $parameters = ['codes' => [$model->code]];

        $this->getJson(route('ubigeo.provinces.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_province_when_departments_filter_is_applied_then_status_ok(): void
    {
        $collection = Province::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (Province $province) => $province->department_id === $model->department_id
        )->count();

        $parameters = ['departments' => [$model->department_id]];

        $this->getJson(route('ubigeo.provinces.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_province_when_department_codes_filter_is_applied_then_status_ok(): void
    {
        $collection = Province::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (Province $province) => $province->department->code === $model->department->code
        )->count();

        $parameters = ['department_codes' => [$model->department->code]];

        $this->getJson(route('ubigeo.provinces.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_province_when_not_filters_are_applied_then_status_ok(): void
    {
        $collection = Province::factory()->count(3)->create();

        $this->getJson(route('ubigeo.provinces.index'))
            ->assertOk()
            ->assertJsonCount($collection->count(), 'data')
        ;
    }

    public function test_show_province_when_id_is_invalid_then_status_not_found(): void
    {
        Province::factory()->count(2)->create();

        $this->getJson(route('ubigeo.provinces.show', 3))
            ->assertNotFound()
        ;
    }

    public function test_show_province_when_id_is_valid_then_status_ok(): void
    {
        $model = Province::factory()->create();

        $this->getJson(route('ubigeo.provinces.show', $model->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
            ])
        ;
    }

    #[DataProvider('integerIdRequiredProvider')]
    public function test_store_province_when_department_id_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['department_id' => $value]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('department_id')
        ;
    }

    public function test_store_province_when_department_id_not_exists_then_status_unprocessable_entity(): void
    {
        $body = $this->getBody(['department_id' => self::$unknownId]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('department_id')
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    public function test_store_province_when_name_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['name' => $value]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
        ;
    }

    public function test_store_province_when_name_exists_then_status_unprocessable_entity(): void
    {
        $province = Province::factory()->create();
        $body = $this->getBody([
            'name' => $province->name,
            'department_id' => $province->department_id,
        ]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    #[DataProvider('moreThan4CharactersProvider')]
    public function test_store_province_when_code_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['code' => $value]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code')
        ;
    }

    public function test_store_province_when_code_exists_then_status_unprocessable_entity(): void
    {
        $province = Province::factory()->create();
        $body = $this->getBody(['code' => $province->code]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code')
        ;
    }

    #[DataProvider('floatProvider')]
    #[DataProvider('requiredProvider')]
    public function test_store_province_when_latitude_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['latitude' => $value]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('latitude')
        ;
    }

    #[DataProvider('floatProvider')]
    #[DataProvider('requiredProvider')]
    public function test_store_province_when_longitude_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['longitude' => $value]);

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('longitude')
        ;
    }

    public function test_store_province_when_data_is_valid_then_status_created(): void
    {
        $body = $this->getBody();

        $this->postJson(route('ubigeo.provinces.store'), $body)
            ->assertCreated()
            ->assertJsonFragment(Arr::except($body, ['department_id']))
        ;
    }

    public function test_update_province_when_id_is_invalid_then_status_not_found(): void
    {
        Province::factory()->count(2)->create();

        $this->putJson(route('ubigeo.provinces.update', 3), $this->getBody())
            ->assertNotFound()
        ;
    }

    public function test_update_province_when_id_is_valid_then_status_accepted(): void
    {
        $model = Province::factory()->create();
        $body = Arr::only($this->getBody(), ['name', 'latitude', 'longitude']);

        $this->putJson(route('ubigeo.provinces.update', $model->id), $body)
            ->assertAccepted()
            ->assertJsonFragment($body)
        ;
    }

    public function test_destroy_province_when_id_is_invalid_then_status_not_found(): void
    {
        Province::factory()->count(2)->create();

        $this->deleteJson(route('ubigeo.provinces.destroy', 3))
            ->assertNotFound()
        ;
    }

    public function test_destroy_province_when_id_is_valid_then_status_accepted(): void
    {
        $model = Province::factory()->create();

        $this->deleteJson(route('ubigeo.provinces.destroy', $model->id))
            ->assertAccepted()
        ;

        $this->assertSoftDeleted('public.provinces', ['id' => $model->id]);
    }

    public function getBody(array $data = []): array
    {
        return array_merge(
            Province::factory()->make()->toArray(),
            $data
        );
    }

    public static function moreThan4CharactersProvider(): array
    {
        return [
            'is more than 4 characters' => ['abcas'],
        ];
    }
}
