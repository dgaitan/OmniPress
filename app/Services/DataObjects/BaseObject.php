<?php

namespace App\Services\DataObjects;

use Illuminate\Database\Eloquent\Model;

abstract class BaseObject {
    /**
     * Field Schema
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Constructor.
     *
     * @param array $attributes
     */
    public function __construct(public array $attributes) {
        $this->schema();
        $this->buildAttributes();
    }

    /**
     * Build the Schema
     *
     * @return void
     */
    abstract protected function schema(): void;

    /**
     * Return values in array format
     *
     * @return array
     */
    public function toArray(string $idField = 'id'): array {
        $attributes = $this->attributes;
        $attributes[$idField] = $this->id;

        return $attributes;
    }

    /**
     * Build the attributes with the right values
     *
     * @return void
     */
    protected function buildAttributes(): void {
        $attributes = [];

        foreach ($this->attributes as $key => $value) {
            if (! in_array($key, array_keys($this->fields))) continue;
            $attributes[$key] = $this->getFieldValue($key, $value);
        }

        $this->attributes = $attributes;
    }

    /**
     * Get the right value of a field
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function getFieldValue(string $key, $value) {
        $field = (object) $this->fields[$key];

        if (! $value || is_null($value)) {
            return $field->default;
        }

        if ($field->type === 'string') {
            $value = (string) $value;
        }

        if ($field->type === 'integer') {
            $value = (int) $value;
        }

        if ($field->type === 'array') {
            $value = (array) $value;
        }

        if ($field->type === 'float') {
            $value = (float) $value;
        }

        if ($field->type === 'money') {
            $value = (int) ((float) $value * 100);
        }

        return $value;
    }

    /**
     * Get Meta Data Formatted
     *
     * @return array
     */
    protected function getMetaData(): array {
        $meta_data = [];

        if ($this->meta_data) {
            $meta_data = collect($this->meta_data)->map(function($meta) {
                return (array) $meta;
            });
        }

        return $meta_data->toArray();
    }

    /**
     * Sync Collection
     *
     * @param string $key
     * @param string $fieldName
     * @param string $dataObject
     * @param Model $parent
     * @return void
     */
    protected function syncCollection(
        string $key,
        string $fieldName,
        string $dataObject,
        Model $parent
    ): void {
        if (in_array($key, $this->attributes) && $this->attributes[$key]) {
            $toAttach = [];

            // First Sync the collection.
            foreach ($this->attributes[$key] as $item) {
                $object = (new $dataObject((array) $item))->sync();
                $object->fill([$fieldName => $parent->id]);
                $object->save();

                $toAttach[] = $object->id;
            }

            if (method_exists($parent->{$key}(), 'sync')) {
                $parent->{$key}()->sync($toAttach);
            }
        }
    }

    /**
     * Register A boolean Field
     *
     * @param string $name
     * @param boolean $default
     * @return void
     */
    protected function boolean(string $name, $default = false) {
        $this->addField($name, 'boolean', $default);
    }

    /**
     * Register a string field
     *
     * @param string $name
     * @param string $default
     * @return void
     */
    protected function string(string $name, $default = '') {
        $this->addField($name, 'string', $default);
    }

    /**
     * Register an Array field
     *
     * @param string $name
     * @param array $default
     * @return void
     */
    protected function array(string $name, $default = []) {
        $this->addField($name, 'array', $default);
    }

    /**
     * Register an integer field
     *
     * @param string $name
     * @param integer $default
     * @return void
     */
    protected function integer(string $name, $default = 0) {
        $this->addField($name, 'integer', $default);
    }

    /**
     * Regsiter a money field
     *
     * @param string $name
     * @param integer $default
     * @return void
     */
    protected function money(string $name, $default = 0) {
        $this->addField($name, 'money', $default);
    }

    /**
     * Register a float field
     *
     * @param string $name
     * @param integer $default
     * @return void
     */
    protected function float(string $name, $default = 0) {
        $this->addField($name, 'float', $default);
    }

    /**
     * Register a field
     *
     * @param string $name
     * @param string $type
     * @param [type] $default
     * @return void
     */
    protected function addField(string $name, string $type, $default = null) {
        $this->fields[$name] = [
            'name' => $name,
            'type' => $type,
            'default' => $default,
        ];
    }

    public function __get(string $key) {
        return isset($this->attributes[$key])
            ? $this->attributes[$key]
            : null;
    }
}
