<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\ValidatedDTO;

class PreOrderDTO extends ValidatedDTO {
    /**
     * Defines the validation rules for the DTO.
     *
     * @return array
     */
    protected function rules(): array {
        return [
            'woo_order_id' => 'required|int',
            'customer_email' => 'required|email',
            'customer_id' => 'required|int',
            'status' => 'required',
            'release_date' => 'required|date',
            'product_id' => 'required|int',
            'product_name' => 'required|string',
            'sub_total' => 'required|int',
            'taxes' => 'required|int',
            'shipping' => 'required |int',
            'total' => 'required|int',
        ];
    }

    /**
     * Defines the default values for the properties of the DTO.
     *
     * @return array
     */
    protected function defaults(): array {
        return [];
    }

    /**
     * Defines the type casting for the properties of the DTO.
     *
     * @return array
     */
    protected function casts(): array {
        return [];
    }

    /**
     * Defines the custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array {
        return [];
    }

    /**
     * Defines the custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes(): array {
        return [];
    }
}
