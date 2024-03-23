<?php

namespace App\Components;

use Illuminate\Support\Facades\DB;

/**
 * Peticiones a Funciones y Procedimientos Almacenados.
 *
 * @method self paramStr(string $parameter, string $value)
 * @method self paramInt(string $parameter, int $value)
 * @method self paramBool(string $parameter, bool $value)
 * @method self paramChar(string $parameter, string $value)
 */
class Functions
{
    public const METHODS = [
        'Str' => \PDO::PARAM_STR,
        'Int' => \PDO::PARAM_INT,
        'Bool' => \PDO::PARAM_BOOL,
        'Char' => \PDO::PARAM_STR_CHAR,
        'Array' => \PDO::PARAM_STR,
        'Json' => \PDO::PARAM_STR,
    ];

    /**
     * Esquema de la tabla (default: public).
     */
    protected string $schema;

    /**
     * Nombre de la funcion a ser ejecutada.
     */
    protected string $functionName;

    /**
     * Alias de respuesta.
     */
    protected string $alias;

    /**
     * Valores a pasar.
     */
    protected array $bindings = [];

    public function __construct(string $functionName, ?string $alias = null)
    {
        $name = explode('.', $functionName);

        $this->schema = 2 == count($name) ? $name[0] : 'public';
        $this->functionName = end($name);
        $this->alias = is_null($alias) ? end($name) : $alias;
    }

    public function get(): mixed
    {
        return DB::selectOne(
            $this->prepareStatement('SELECT'),
            $this->bindings
        )->{$this->alias} ?? null;
    }

    public function getRow(): mixed
    {
        return DB::selectOne(
            $this->prepareStatement('SELECT * FROM'),
            $this->bindings
        ) ?? null;
    }

    /**
     * Retorna una coleccion de objetos.
     */
    public function getJson(): ?array
    {
        return json_decode($this->get(), true);
    }

    public function getAll(): array
    {
        return DB::select(
            $this->prepareStatement('SELECT * FROM'),
            $this->bindings
        ) ?? null;
    }

    public function paramInt(string $param, $value): self
    {
        if (is_numeric($value)) {
            $value = intval($value);
        }

        $this->bindings[$this->prefix($param)] = $value;

        return $this;
    }

    public function paramFloat(string $param, $value): self
    {
        if (is_numeric($value) and is_float($value)) {
            $value = floatval($value);
        }

        $this->bindings[$this->prefix($param)] = $value;

        return $this;
    }

    public function paramStr(string $param, $value): self
    {
        $value = 0 === strlen(trim(strval($value)))
            ? null
            : trim(strval($value));

        $this->bindings[$this->prefix($param)] = $value;

        return $this;
    }

    public function paramBool(string $param, $value): self
    {
        $this->bindings[$this->prefix($param)] = boolval($value);

        return $this;
    }

    public function paramChar(string $param, $value): self
    {
        $value = 0 === strlen(trim(strval($value)))
            ? null
            : trim(strval($value));

        $this->bindings[$this->prefix($param)] = substr($value, 0, 1);

        return $this;
    }

    public function paramPoint(string $param, ?array $value): self
    {
        $point = null;

        if (is_array($value) and 2 === count(array_values($value))) {
            $point = $value;
        }

        $this->bindings[$this->prefix($param)] = implode(',', $point);

        return $this;
    }

    public static function call(string $functionName, ?string $alias = null): self
    {
        return new static($functionName, $alias);
    }

    private function getBindings(): string
    {
        return implode(', ', array_keys($this->bindings));
    }

    private function prefix(string $value): string
    {
        if (':' !== substr($value, 0, 1)) {
            return ":{$value}";
        }

        return $value;
    }

    private function prepareStatement($caller): string
    {
        return sprintf(
            '%s %s.%s(%s) AS %s',
            $caller,
            $this->schema,
            $this->functionName,
            $this->getBindings(),
            $this->alias
        );
    }
}
