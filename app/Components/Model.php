<?php

namespace App\Components;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Clase base para el manejo de los modelos.
 *
 * @method static static|null find(int $id) Busca un modelo.
 */
abstract class Model extends BaseModel
{
    protected $dateFormat = \DateTime::ATOM;

    protected $hidden = [
        'created_by',
        'deleted_by',
        'deleted_at',
        'modified_by',
        'updated_by',
    ];

    protected $userId;

    public function __construct(array $attributes = [])
    {
        $this->setUser(Config::get('user.id'));

        parent::__construct($attributes);
    }

    /**
     * Define las reglas de validación para el model.
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Inicia la validación de los datos con las reglas de validación
     * establecidas en el metodo `rules()`.
     *
     * @return static
     */
    public function validate()
    {
        $this->runValidation($this->rules(), $this->getAttributes());

        return $this;
    }

    /**
     * Establece el Id del usuario, este valor es usado para auditor.
     */
    public function setUser(?int $userId)
    {
        $this->userId = to_int($userId);

        return $this;
    }

    /**
     * Inicia la validación de los datos.
     */
    protected function runValidation(array $rules, array $attributes = null): void
    {
        $validator = Validator::make(
            $attributes ?? $this->getAttributes(),
            $rules
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
