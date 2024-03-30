<?php

namespace Tests\Feature\Http\Controllers\Ubigeo;

use App\Models\Ubigeo\Department;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('ubigeo')]
class DepartmentsControllerTest extends TestCase
{
    public function test_index_department_when_code_filter_is_applied_then_status_ok(): void
    {
        $collection = Department::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(
            fn (Department $department) => $department->code === $model->code
        )->count();

        $parameters = ['codes' => [$model->code]];

        $this->getJson(route('ubigeo.departments.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_department_when_not_filters_are_applied_then_status_ok(): void
    {
        $collection = Department::factory()->count(3)->create();

        $this->getJson(route('ubigeo.departments.index'))
            ->assertOk()
            ->assertJsonCount($collection->count(), 'data')
        ;
    }

    public function test_show_department_when_id_is_invalid_then_status_not_found(): void
    {
        Department::factory()->count(2)->create();

        $this->getJson(route('ubigeo.departments.show', 3))
            ->assertNotFound()
        ;
    }

    public function test_show_department_when_id_is_valid_then_status_ok(): void
    {
        $model = Department::factory()->create();

        $this->getJson(route('ubigeo.departments.show', $model->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $model->id,
                'name' => $model->name,
                'code' => $model->code,
            ])
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    public function test_store_department_when_name_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['name' => $value]);

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
        ;
    }

    public function test_store_department_when_name_exists_then_status_unprocessable_entity(): void
    {
        $department = Department::factory()->create();
        $body = $this->getBody(['name' => $department->name]);

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name')
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    #[DataProvider('moreThan2CharactersProvider')]
    public function test_store_department_when_code_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['code' => $value]);

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code')
        ;
    }

    public function test_store_department_when_code_exists_then_status_unprocessable_entity(): void
    {
        $department = Department::factory()->create();
        $body = $this->getBody(['code' => $department->code]);

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('code')
        ;
    }

    #[DataProvider('floatProvider')]
    #[DataProvider('requiredProvider')]
    public function test_store_department_when_latitude_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['latitude' => $value]);

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('latitude')
        ;
    }

    #[DataProvider('floatProvider')]
    #[DataProvider('requiredProvider')]
    public function test_store_department_when_longitude_is_missing_then_status_unprocessable_entity($value): void
    {
        $body = $this->getBody(['longitude' => $value]);

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('longitude')
        ;
    }

    public function test_store_department_when_data_is_valid_then_status_created(): void
    {
        $body = $this->getBody();

        $this->postJson(route('ubigeo.departments.store'), $body)
            ->assertCreated()
            ->assertJsonFragment($body)
        ;
    }

    public function test_update_department_when_id_is_invalid_then_status_not_found(): void
    {
        Department::factory()->count(2)->create();

        $this->putJson(route('ubigeo.departments.update', 3), $this->getBody())
            ->assertNotFound()
        ;
    }

    public function test_update_department_when_id_is_valid_then_status_accepted(): void
    {
        $model = Department::factory()->create();
        $body = Arr::only($this->getBody(), ['name', 'latitude', 'longitude']);

        $this->putJson(route('ubigeo.departments.update', $model->id), $body)
            ->assertAccepted()
            ->assertJsonFragment($body)
        ;
    }

    public function test_destroy_department_when_id_is_invalid_then_status_not_found(): void
    {
        Department::factory()->count(2)->create();

        $this->deleteJson(route('ubigeo.departments.destroy', 3))
            ->assertNotFound()
        ;
    }

    public function test_destroy_department_when_id_is_valid_then_status_accepted(): void
    {
        $model = Department::factory()->create();

        $this->deleteJson(route('ubigeo.departments.destroy', $model->id))
            ->assertAccepted()
        ;

        $this->assertSoftDeleted('public.departments', ['id' => $model->id]);
    }

    public function getBody(array $data = []): array
    {
        return array_merge(
            Department::factory()->make()->toArray(),
            $data
        );
    }

    public static function moreThan2CharactersProvider(): array
    {
        return [
            'is more than 2 characters' => ['abc'],
        ];
    }
}
