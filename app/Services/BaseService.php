<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    protected array $data;

    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct(array $data = []) {
        $this->data = $data;
        $this->handle();
    }

    /**
     * Dispatch Service
     *
     * @param array $data
     * @return static
     */
    public static function dispatch(array $data = []): static
    {
        return new static($data);
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
     * @return boolean
     */
    public function validate(): bool
    {
        $validator = Validator::make($this->data, $this->rules());

        if ($validator->fails()) {
            throw new Exception($validator->errors());
        }

        $this->data = $validator->validated();

        return true;
    }

    /**
     * Get a data like property
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        if (! array_key_exists($key, $this->data)) {
            throw new Exception(
                sprintf('Invalid Argument Call in Service %s', static::class)
            );
        }

        return $this->data[$key];
    }
}
