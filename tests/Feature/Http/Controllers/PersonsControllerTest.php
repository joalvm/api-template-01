<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Person;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PersonsControllerTest extends TestCase
{
    public function test_index_person_when_filter_document_types_is_applied_then_status_ok(): void
    {
        $collection = Person::factory()->count(3)->create();
        $model = $collection->first();

        $parameters = ['document_types' => $model->document_type_id];

        $this->getJson(route('persons.index', $parameters))
            ->assertOk()
            ->assertJsonCount(1, 'data')
        ;
    }

    public function test_index_person_when_filter_gender_is_applied_then_status_ok(): void
    {
        $collection = Person::factory()->count(3)->create();
        $model = $collection->first();

        $parameters = ['gender' => $model->gender->value];

        $total = $collection->filter(fn ($item) => $item->gender === $model->gender)->count();

        $this->getJson(route('persons.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }

    public function test_index_person_when_filter_id_documents_is_applied_then_status_ok(): void
    {
        $collection = Person::factory()->count(3)->create();
        $model = $collection->first();

        $parameters = ['id_documents' => $model->id_document];

        $this->getJson(route('persons.index', $parameters))
            ->assertOk()
            ->assertJsonCount(1, 'data')
        ;
    }

    public function test_index_person_when_not_filters_are_applied_then_status_ok(): void
    {
        Person::factory()->count(3)->create();

        $this->get(route('persons.index'))
            ->assertOk()
            ->assertJsonCount(3, 'data')
        ;
    }

    public function test_show_person_when_id_is_invalid_then_status_not_found()
    {
        Person::factory()->count(2)->create();

        $this->getJson(route('persons.show', 3))
            ->assertNotFound()
        ;
    }

    public function test_show_person_when_id_is_valid_then_then_status_ok()
    {
        $model = Person::factory()->create();

        $this->getJson(route('persons.show', $model->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $model->id,
                'names' => $model->names,
                'last_names' => $model->last_names,
            ])
        ;
    }

    #[DataProvider('alphaSpaceRequiredProvider')]
    public function test_store_person_when_names_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['names' => $value]);

        $this->postJson(route('persons.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('names')
        ;
    }

    #[DataProvider('alphaSpaceRequiredProvider')]
    public function test_store_person_when_last_names_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['last_names' => $value]);

        $this->postJson(route('persons.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('last_names')
        ;
    }

    #[DataProvider('enumRequiredProvider')]
    public function test_store_person_when_gender_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['gender' => $value]);

        $this->postJson(route('persons.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('gender')
        ;
    }

    #[DataProvider('integerIdRequiredProvider')]
    public function test_store_person_when_document_type_id_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['document_type_id' => $value]);

        $this->postJson(route('persons.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('document_type_id')
        ;
    }

    #[DataProvider('StringRequiredProvider')]
    public function test_store_person_when_id_document_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['id_document' => $value]);

        $this->postJson(route('persons.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('id_document')
        ;
    }

    #[DataProvider('EmailRequiredProvider')]
    public function test_store_person_when_email_is_invalid_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['email' => $value]);

        $this->postJson(route('persons.store'), $body)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email')
        ;
    }

    public function test_store_person_when_data_is_valid_then_status_created()
    {
        $body = $this->getBody();

        $this->postJson(route('persons.store'), $body)
            ->assertCreated()
            ->assertJsonFragment([
                'names' => $body['names'],
                'last_names' => $body['last_names'],
            ])
        ;
    }

    public function test_update_person_when_id_is_invalid_then_status_not_found()
    {
        $body = $this->getBody();

        $this->putJson(route('persons.update', self::$unknownId), $body)
            ->assertNotFound()
        ;
    }

    public function test_update_person_when_id_is_valid_then_status_accepted()
    {
        $model = Person::factory()->create();
        $body = Arr::only($this->getBody(), ['names', 'last_names']);

        $this->putJson(route('persons.update', $model->id), $body)
            ->assertAccepted()
            ->assertJsonFragment([
                'names' => $body['names'],
                'last_names' => $body['last_names'],
            ])
        ;
    }

    public function test_destroy_person_when_id_is_invalid_then_status_not_found()
    {
        $this->deleteJson(route('persons.destroy', self::$unknownId))
            ->assertNotFound()
        ;
    }

    public function test_destroy_person_when_id_is_valid_then_status_accepted()
    {
        $model = Person::factory()->create();

        $this->deleteJson(route('persons.destroy', $model->id))
            ->assertAccepted()
        ;

        $this->assertSoftDeleted('public.persons', ['id' => $model->id]);
    }

    private function getBody(array $data = []): array
    {
        return array_merge(Person::factory()->make()->toArray(), $data);
    }
}
