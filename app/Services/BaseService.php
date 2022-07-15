<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Validator;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseService
{
    public array $_data = [];

    public bool $withoutValidations = false;

    /**
     * Dispatch Service
     *
     * @param  array  $data
     * @return static
     */
    public static function dispatch(...$args): static
    {
        return (new static(...$args))->execute();
    }

    /**
     * Dispatch this service without validate
     * params
     *
     * @param  mixed  ...$args
     * @return static
     */
    public static function dispatchWithoutValidations(...$args): static
    {
        return (new static(...$args))
            ->skipValidations()
            ->execute();
    }

    /**
     * Set service without validations
     *
     * @return self
     */
    public function skipValidations(): self
    {
        $this->withoutValidations = true;

        return $this;
    }

    /**
     * Execute the service
     *
     * @return static
     */
    public function execute(): static
    {
        $this->getProperties();
        $this->validate();
        $this->handle();

        return $this;
    }

    /**
     * Handle the service
     *
     * @return void
     */
    public function handle()
    {
        $this->validate();
    }

    /**
     * Service field rules
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Validate
     *
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->withoutValidations) {
            return true;
        }

        $validator = Validator::make($this->_data, $this->rules());

        if ($validator->fails()) {
            throw new Exception($validator->errors());
        }

        $this->_data = $validator->validated();

        return true;
    }

    /**
     * GEt propertties
     *
     * @return void
     */
    protected function getProperties(): void
    {
        if ($this->withoutValidations) {
            return;
        }

        $class = new ReflectionClass($this);
        $props = $class->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        if ($props) {
            foreach ($props as $prop) {
                $this->_data[$prop->getName()] = $this->{$prop->getName()};
            }
        }
    }
}
