<?php

namespace App\Models;

use Frame\Database\Model;
use Frame\Validation\Validator;
use Frame\Exceptions\ValidationException;

abstract class Product extends Model
{
    /**
     * The namespace associated with the model.
     * 
     * @var string
     */
    protected static string $namespace = 'App\\Models\\';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected static string $table = 'products';

    /**
     * Product SKU value.
     * 
     * @var ?string
     */
    protected ?string $sku = null;

    /**
     * Product name.
     * 
     * @var ?string
     */
    protected ?string $name = null;

    /**
     * Product price value.
     * 
     * @var ?float
     */
    protected ?float $price = null;

    /**
     * Product special attribute.
     * 
     * @var ?string
     */
    protected ?string $attribute = null;

    /**
     * Fill the product with the data.
     * 
     * @param array $data
     * @return $this
     */
    public function fill(array $data): static
    {
        foreach ($data as $field => $value) {
            if (!method_exists($this, 'set' . ucfirst(strtolower($field)))) continue;

            $setter = 'set' . ucfirst(strtolower($field));

            $this->$setter($value);
        }

        return $this;
    }

    /**
     * Validate the product.
     * 
     * @param array $inputs
     * @return $this
     */
    public function validate(array $inputs): static
    {
        $validator = new Validator();

        $validator->validate($inputs, [
            'sku' =>    ['required'],
            'name' =>   ['required'],
            'price' =>  ['required', 'numeric'],
        ]);

        $this->validateAttributes($validator);
        $this->setAttribute();

        $this->validateSku($this->getSku(), $validator);

        if ($validator->hasErrors()) {
            throw new ValidationException($validator->errors());
        }

        return $this;
    }

    /**
     * Validate that the SKU is unique.
     * 
     * @param ?string $sku
     * @param \Frame\Validation\Validator $validator
     * @return $this
     */
    public function validateSku(?string $sku, Validator $validator): static
    {
        if (empty($sku)) return $this;

        $sql = $this->selectQuery('*', static::table(), 'sku = :sku');

        $result = $this->query($sql, [':sku' => $sku]);

        if ($result->rowCount() > 0) {
            $validator->addError('sku', 'This SKU already exists');
        }

        return $this;
    }

    /**
     * Save the product to the database.
     */
    public function save()
    {
        $sql = $this->insertQuery(['sku', 'name', 'price', 'attribute']);

        $this->query($sql, [
            $this->getSku(),
            $this->getName(),
            $this->getPrice(),
            $this->getAttribute(),
        ]);

        return [
            'message' => 'Product created successfully',
            'data' => $this->last()
        ];
    }

    /**
     * Set the SKU of the product.
     * 
     * @param string $sku
     * @return $this
     */
    public function setSku($sku): static
    {
        $this->sku = $sku;
        return $this;
    }

    /**
     * Set the name of the product.
     * 
     * @param string $name
     * @return $this
     */
    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set the price of the product.
     * 
     * @param int|float|string $price
     * @return $this
     */
    public function setPrice(int|float|string $price): static
    {
        $this->price = floatval($price);
        return $this;
    }

    /**
     * Set the product attribute.
     * 
     * @return $this
     */
    abstract public function setAttribute(): static;

    /**
     * Get the SKU value.
     * 
     * @return ?string
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * Get the product name.
     * 
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Get the product price.
     * 
     * @return ?int
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * Get the product attribute.
     * 
     * @return ?string;
     */
    public function getAttribute(): ?string
    {
        return $this->attribute;
    }

    /**
     * Validate the product attributes.
     * 
     * @param \Frame\Validation\Validator $validator
     * @return static
     */
    abstract public function validateAttributes(Validator $validator): static;
}
