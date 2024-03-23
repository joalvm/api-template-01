<?php

namespace App\Models;

use App\Components\Model;
use App\Enums\Gender;
use App\Models\Client\Collaborator;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Enum;

/**
 * @property      int          $id
 * @property      string       $names
 * @property      string       $last_names
 * @property      Gender       $gender
 * @property      int          $document_type_id
 * @property      string       $id_document
 * @property      string|null  $email
 * @property-read DocumentType $documentType
 * @property-read User         $user
 * @property-read Collaborator $collaborator
 */
class Person extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'public.persons';

    protected $fillable = [
        'names',
        'last_names',
        'gender',
        'document_type_id',
        'id_document',
        'email',
    ];

    protected $casts = [
        'document_type_id' => 'integer',
        'gender' => Gender::class,
    ];

    public function rules(): array
    {
        return [
            'names' => ['required', 'string'],
            'last_names' => ['required', 'string'],
            'gender' => ['required', new Enum(Gender::class)],
            'document_type_id' => [
                'required',
                'integer',
                $this->ruleExistsDocumentType(),
            ],
            'id_document' => [
                'required',
                'string',
                $this->ruleUniqueIdDocument(),
            ],
            'email' => ['nullable', 'email', $this->ruleUniqueEmail()],
        ];
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'person_id', 'id');
    }

    private function ruleUniqueEmail(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->query()->where('email', $value);

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleExistsDocumentType(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->documentType()->getQuery();

            if (!$query->exists()) {
                $fail(Lang::get('validation.exists', ['attribute' => $attribute]));
            }
        };
    }

    private function ruleUniqueIdDocument(): \Closure
    {
        return function ($attribute, $value, $fail) {
            $query = $this->query()->where('id_document', $value);

            if ($this->exists) {
                $query->where('id', '<>', $this->getAttribute('id'));
            }

            if ($query->exists()) {
                $fail(Lang::get('validation.unique', ['attribute' => $attribute]));
            }
        };
    }
}
