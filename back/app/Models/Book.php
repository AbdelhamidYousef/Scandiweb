<?php

namespace App\Models;

use Frame\Validation\Validator;

class Book extends Product
{
    /**
     * Book special attribute (Weight).
     * 
     * @var ?string
     */
    protected ?string $weight = null;

    /**
     * Set the product weight.
     * 
     * @param string $value
     * @return $this
     */
    public function setWeight(string $value): static
    {
        $this->weight = $value;
        return $this;
    }

    /**
     * Get the product weight.
     * 
     * @return ?string
     */
    public function getWeight(): ?string
    {
        return $this->weight;
    }

    /**
     * Validate the Book attributes.
     * 
     * @param \Frame\Validation\Validator $validator
     * @return $this
     */
    public function validateAttributes(Validator $validator): static
    {
        $validator->validate(
            ['weight' => $this->getWeight()],
            ['weight' => ['required']]
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
        $this->attribute = 'Weight: ' . $this->getWeight() . 'KG';
        return $this;
    }
}
