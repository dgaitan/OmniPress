<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\ValidatedDTO;

class MembershipDTO extends ValidatedDTO {
    /**
     * Defines the validation rules for the DTO.
     *
     * @return array
     */
    protected function rules(): array {
        return [
            'price' => 'required|integer',
            'customer_id' => 'required|integer',
            'email' => 'required|email:rfc,dns',
            'username' => 'required|string',
            'order_id' => 'required|integer',
            'points' => 'required|integer',
            'gift_product_id' => 'nullable|integer',
            'product_id' => 'nullable|integer',
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
