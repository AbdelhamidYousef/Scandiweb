<?php

namespace Frame\Validation\Rules;

class Numeric
{
    /**
     * Validate that the given field has a numeric value.
     * 
     * @param   array   $data
     * @param   string  $field
     * @param   array   $params
     * @return  bool
     */
    public function validate(array $data, string $field, array $params = []): bool
    {
        return is_numeric($data[$field]);
    }

    /**
     * Get the validation error message.
     * 
     * @return  string
     */
    public function message(): string
    {
        return 'This field must be a number.';
    }
}
