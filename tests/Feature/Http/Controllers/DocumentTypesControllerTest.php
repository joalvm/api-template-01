<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Support\Arr;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DocumentTypesControllerTest extends TestCase
{
    public function test_index_document_type_when_char_type_filter_is_applied_then_status_ok(): void
    {
        $this->assertFilterCount('char_type');
    }

    public function test_index_document_type_when_length_types_filter_is_applied_then_status_ok(): void
    {
        $this->assertFilterCount('length_types');
    }

    public function test_index_document_type_when_not_filters_are_applied_then_status_ok(): void
    {
        DocumentType::factory()->count(3)->create();

        $this->get(route('document_types.index'))
            ->assertOk()
            ->assertJsonCount(3, 'data')
        ;
    }

    public function test_show_document_types_when_id_is_invalid_then_resource_not_found()
    {
        DocumentType::factory()->count(3)->create();

        $this->getJson(route('document_types.show', 4))->assertNotFound();
    }

    public function test_show_document_types_when_id_is_valid_then_resource_found()
    {
        $model = DocumentType::factory()->create();

        $this->getJson(route('document_types.show', $model->id))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $model->id,
                'name' => $model->name,
                'abbr' => $model->abbr,
            ])
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    public function test_store_document_types_when_name_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['name' => $value]);

        $this->postJson(route('document_types.store'), $body)
            ->assertStatus(422)
            ->assertJsonValidationErrors('name')
        ;
    }

    #[DataProvider('stringRequiredProvider')]
    public function test_store_document_types_when_abbr_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['abbr' => $value]);

        $this->postJson(route('document_types.store'), $body)
            ->assertStatus(422)
            ->assertJsonValidationErrors('abbr')
        ;
    }

    #[DataProvider('enumRequiredProvider')]
    public function test_store_document_types_when_length_type_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['length_type' => $value]);

        $this->postJson(route('document_types.store'), $body)
            ->assertStatus(422)
            ->assertJsonValidationErrors('length_type')
        ;
    }

    #[DataProvider('smallIntegerPositiveRequiredProvider')]
    public function test_store_document_types_when_length_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['length' => $value]);

        $this->postJson(route('document_types.store'), $body)
            ->assertStatus(422)
            ->assertJsonValidationErrors('length')
        ;
    }

    #[DataProvider('enumRequiredProvider')]
    public function test_store_document_types_when_char_type_is_missing_then_status_unprocessable_entity($value)
    {
        $body = $this->getBody(['char_type' => $value]);

        $this->postJson(route('document_types.store'), $body)
            ->assertStatus(422)
            ->assertJsonValidationErrors('char_type')
        ;
    }

    public function test_store_document_types_when_data_is_valid_then_status_created()
    {
        $body = $this->getBody();

        $this->postJson(route('document_types.store'), $body)
            ->assertCreated()
            ->assertJsonFragment($body)
        ;
    }

    public function test_update_document_types_when_id_is_invalid_then_status_not_found()
    {
        DocumentType::factory()->count(2)->create();

        $this->putJson(route('document_types.update', 3), $this->getBody())
            ->assertNotFound()
        ;
    }

    public function test_update_document_types_when_id_is_valid_then_status_accepted()
    {
        $model = DocumentType::factory()->create();
        $body = Arr::only($this->getBody(), ['name', 'abbr']);

        $this->putJson(route('document_types.update', $model->id), $body)
            ->assertAccepted()
            ->assertJsonFragment($body)
        ;
    }

    public function test_destrooy_document_types_when_id_is_invalid_then_status_not_found()
    {
        DocumentType::factory()->count(2)->create();

        $this->deleteJson(route('document_types.destroy', 3))
            ->assertNotFound()
        ;
    }

    public function test_destroy_document_types_when_id_is_valid_then_status_accepted()
    {
        $model = DocumentType::factory()->create();

        $this->deleteJson(route('document_types.destroy', $model->id))
            ->assertAccepted()
        ;

        $this->assertSoftDeleted('public.document_types', ['id' => $model->id]);
    }

    private function getBody(array $data = []): array
    {
        return array_merge(DocumentType::factory()->make()->toArray(), $data);
    }

    private function assertFilterCount(string $filter): void
    {
        $collection = DocumentType::factory()->count(3)->create();
        $model = $collection->first();
        $total = $collection->filter(fn ($item) => $item->$filter === $model->$filter)->count();

        $parameters = [$filter => $model->$filter];

        $this->get(route('document_types.index', $parameters))
            ->assertOk()
            ->assertJsonCount($total, 'data')
        ;
    }
}
