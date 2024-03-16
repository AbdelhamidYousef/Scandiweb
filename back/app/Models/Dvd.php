<?php

namespace App\Models;

use Frame\Validation\Validator;

class Dvd extends Product
{
    /**
     * DVD special attribute (Size).
     * 
     * @var ?string
     */
    protected ?string $size = null;

    /**
     * Set the product size.
     * 
     * @param string $value
     * @return $this
     */
    public function setSize(string $value): static
    {
        $this->size = $value;
        return $this;
    }

    /**
     * Get the product size.
     * 
     * @return ?string
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * Validate the DVD attributes.
     * 
     * @param \Frame\Validation\Validator $validator
     * @return $this
     */
    public function validateAttributes(Validator $validator): static
    {
        $validator->validate(
            ['size' => $this->getSize()],
            ['size' => ['required']]
        );

        return $this;
    }

    /**
     * Set the product attribute.
     * 
     * @return $this
     */
    public function setAttribute(): static
    {
        $this->attribute = 'Size: ' . $this->getSize() . ' MB';
        return $this;
    }
}
