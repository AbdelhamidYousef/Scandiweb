<?php

namespace App\Models;

use Frame\Validation\Validator;

class Furniture extends Product
{
    /**
     * Furniture special attribute (Height).
     * 
     * @var ?string
     */
    protected ?string $height = null;

    /**
     * Furniture special attribute (Width).
     * 
     * @var ?string
     */
    protected ?string $width = null;

    /**
     * Furniture special attribute (Length).
     * 
     * @var ?string
     */
    protected ?string $length = null;

    /**
     * Set the product height.
     * 
     * @param string $value
     * @return $this
     */
    public function setHeight(string $value): static
    {
        $this->height = $value;
        return $this;
    }

    /**
     * Get the product height.
     * 
     * @return ?string
     */
    public function getHeight(): ?string
    {
        return $this->height;
    }

    /**
     * Set the product width.
     * 
     * @param string $value
     * @return $this
     */
    public function setWidth(string $value): static
    {
        $this->width = $value;
        return $this;
    }

    /**
     * Get the product width.
     * 
     * @return ?string
     */
    public function getWidth(): ?string
    {
        return $this->width;
    }

    /**
     * Set the product length.
     * 
     * @param string $value
     * @return $this
     */
    public function setLength(string $value): static
    {
        $this->length = $value;
        return $this;
    }

    /**
     * Get the product length.
     * 
     * @return ?string
     */
    public function getLength(): ?string
    {
        return $this->length;
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
            [
                'height' => $this->getHeight(),
                'width' => $this->getWidth(),
                'length' => $this->getLength(),
            ],
            [
                'height' => ['required'],
                'width' => ['required'],
                'length' => ['required'],
            ]
        );

        return $this;
    }

    /**
     * Set the product attribute.
     * 
     * @param string $value
     * @return $this
     */
    public function setAttribute(): static
    {
        $this->attribute = sprintf('Dimension: %sx%sx%s', $this->getHeight(), $this->getWidth(), $this->getLength());
        return $this;
    }
}
