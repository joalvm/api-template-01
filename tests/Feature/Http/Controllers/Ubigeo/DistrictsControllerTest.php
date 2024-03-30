<?php

namespace Tests\Feature\Http\Controllers\Ubigeo;

use App\Models\Ubigeo\District;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('ubigeo')]
class DistrictsControllerTest extends TestCase
{
    public function test_index_district_when_code_filter_is_applied_then_status_ok(): void
    {
        $collection = District::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (District $district) => $district->code === $model->code
        )->count();

        $parameters = ['codes' => [$model->code]];

        $this->getJson(route('ubigeo.districts.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_district_when_provinces_filter_is_applied_then_status_ok(): void
    {
        $collection = District::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (District $district) => $district->province_id === $model->province_id
        )->count();

        $parameters = ['provinces' => [$model->province_id]];

        $this->getJson(route('ubigeo.districts.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_district_when_province_codes_filter_is_applied_then_status_ok(): void
    {
        $collection = District::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (District $district) => $district->province->code === $model->province->code
        )->count();

        $parameters = ['province_codes' => [$model->province->code]];

        $this->getJson(route('ubigeo.districts.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_district_when_departments_filter_is_applied_then_status_ok(): void
    {
        $collection = District::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (District $district) => $district->province->department_id === $model->province->department_id
        )->count();

        $parameters = ['departments' => [$model->province->department_id]];

        $this->getJson(route('ubigeo.districts.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_district_when_department_codes_filter_is_applied_then_status_ok(): void
    {
        $collection = District::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (District $district) => $district->province->department->code === $model->province->department->code
        )->count();

        $parameters = ['department_codes' => [$model->province->department->code]];

        $this->getJson(route('ubigeo.districts.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_district_when_not_filters_are_applied_then_status_ok(): void
    {
        $collection = District::factory()->count(3)->create();

        $this->getJson(route('ubigeo.districts.index'))
            ->assertOk()
            ->assertJsonCount($collection->count(), 'data')
        ;
    }

    public function test_show_district_when_id_is_invalid_then_status_not_found(): void
    {
        District::factory()->count(2)->create();

        $this->getJson(route('ubigeo.districts.show', 3))
            ->assertNotFound()
        ;
    }

    public function test_show_district_when_id_is_valid_then_status_ok(): void
    {
        $model = District::factory()->create();

        $this->getJson(route('ubigeo.districts.show', $model->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
            ])
        ;
    }

    #[DataProvider('integerIdRequiredProvider')]
    public function test_store_district_when_province_id_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['province_id' => $value]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('province_id')
        ;
    }

    public function test_store_district_when_province_id_not_exists_then_status_unprocessable_entity(): void
    {
        $body = $this->getBody(['province_id' => self::$unknownId]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('province_id')
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    public function test_store_district_when_name_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['name' => $value]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
        ;
    }

    public function test_store_district_when_name_exists_then_status_unprocessable_entity(): void
    {
        $district = District::factory()->create();
        $body = $this->getBody([
            'name' => $district->name,
            'province_id' => $district->province_id,
        ]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    #[DataProvider('moreThan6CharactersProvider')]
    public function test_store_district_when_code_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['code' => $value]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code')
        ;
    }

    public function test_store_district_when_code_exists_then_status_unprocessable_entity(): void
    {
        $district = District::factory()->create();
        $body = $this->getBody(['code' => $district->code]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code')
        ;
    }

    #[DataProvider('floatProvider')]
    #[DataProvider('requiredProvider')]
    public function test_store_district_when_latitude_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['latitude' => $value]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('latitude')
        ;
    }

    #[DataProvider('floatProvider')]
    #[DataProvider('requiredProvider')]
    public function test_store_district_when_longitude_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['longitude' => $value]);

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('longitude')
        ;
    }

    public function test_store_district_when_data_is_valid_then_status_created(): void
    {
        $body = $this->getBody();

        $this->postJson(route('ubigeo.districts.store'), $body)
            ->assertCreated()
            ->assertJsonFragment(Arr::except($body, ['province_id']))
        ;
    }

    public function test_update_district_when_id_is_invalid_then_status_not_found(): void
    {
        District::factory()->count(2)->create();

        $this->putJson(route('ubigeo.districts.update', 3), $this->getBody())
            ->assertNotFound()
        ;
    }

    public function test_update_district_when_id_is_valid_then_status_accepted(): void
    {
        $model = District::factory()->create();
        $body = Arr::only($this->getBody(), ['name', 'latitude', 'longitude']);

        $this->putJson(route('ubigeo.districts.update', $model->id), $body)
            ->assertAccepted()
            ->assertJsonFragment($body)
        ;
    }

    public function test_destroy_district_when_id_is_invalid_then_status_not_found(): void
    {
        District::factory()->count(2)->create();

        $this->deleteJson(route('ubigeo.districts.destroy', 3))
            ->assertNotFound()
        ;
    }

    public function test_destroy_district_when_id_is_valid_then_status_accepted(): void
    {
        $model = District::factory()->create();

        $this->deleteJson(route('ubigeo.districts.destroy', $model->id))
            ->assertAccepted()
        ;

        $this->assertSoftDeleted('public.districts', ['id' => $model->id]);
    }

    public function getBody(array $data = []): array
    {
        return array_merge(
            District::factory()->make()->toArray(),
            $data
        );
    }

    public static function moreThan6CharactersProvider(): array
    {
        return [
            'is more than 4 characters' => ['abcasas'],
        ];
    }
}
