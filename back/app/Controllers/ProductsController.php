<?php

namespace App\Controllers;

use App\Models\Product;
use Frame\Exceptions\ValidationException;
use Frame\Http\Request;

class ProductsController
{
    /**
     * Display list of products.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a new product.
     */
    public function store(Request $request)
    {
        // Make sure that the type is present.
        if (!isset($request->inputs()['type'])) {
            throw new ValidationException(['type' => 'This field is required.']);
        }

        // Generate a new product instance based on the type.
        $product = $this->generateProduct($request->inputs('type'));

        // Fill the product with the request data.
        $product->fill($request->inputs());

        // Validate the product.
        $product->validate($request->inputs());

        return $product->save();
    }

    /**
     * Delete an array of products.
     */
    public function massDelete(Request $request)
    {
        $ids = $request->inputs()['ids'];

        if (count($ids) === 0) {
            return ["message" => "No products to delete"];
        }

        array_walk($ids, function ($id) {
            Product::destroy($id);
        });

        return [
            "message" => "Products deleted successfully"
        ];
    }

    /**
     * Generate a new product instance.
     * 
     * @param  string  $type
     * @return \App\Models\Product
     * 
     * @throws \Frame\Exceptions\ValidationException
     */
    private function generateProduct($type)
    {
        if (class_exists($class = Product::namespace() . ucfirst(strtolower($type)))) {
            return new $class;
        }

        throw new ValidationException(['type' => 'Invalid type.']);
    }
}
