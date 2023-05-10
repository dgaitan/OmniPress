<?php

namespace App\DTOs;

use WendellAdriel\ValidatedDTO\ValidatedDTO;

class PreSaleProductDTO extends ValidatedDTO {
    /**
     * Defines the validation rules for the DTO.
     *
     * @return array
     */
    protected function rules(): array {
        return [
            'product_name' => 'required|string',
            'woo_product_id' => 'required|int',
            'release_date' => 'required|date',
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
