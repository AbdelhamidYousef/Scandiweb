<?php

namespace Frame\Validation\Rules;

class Required
{
    /**
     * Validate that the given field is not empty.
     * 
     * @param   array   $data
     * @param   string  $field
     * @param   array   $params
     * @return  bool
     */
    public function validate(array $data, string $field, array $params = []): bool
    {
        return isset($data[$field]) && !empty($data[$field]);
    }

    /**
     * Get the validation error message.
     * 
     * @return  string
     */
    public function message(): string
    {
        return 'This field is required.';
    }
}
