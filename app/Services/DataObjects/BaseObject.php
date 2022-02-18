<?php

namespace App\Services\DataObjects;

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
    public function toArray(): array {
        return $this->attributes;
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
