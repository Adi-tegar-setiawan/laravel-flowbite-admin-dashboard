<?php

namespace App\Repositories;

use App\Models\ProductAttribute;
use App\Repositories\Interfaces\ProductAttributeRepositoryInterface;

class ProductAttributeRepository implements ProductAttributeRepositoryInterface
{
    public function getByProductId(int $productId)
    {
        return ProductAttribute::where('product_id', $productId)->get();
    }

    public function create(array $data)
    {
        return ProductAttribute::create($data);
    }

    public function update(int $id, array $data)
    {
        $attribute = ProductAttribute::findOrFail($id);
        $attribute->update($data);
        return $attribute;
    }

    public function delete(int $id)
    {
        $attribute = ProductAttribute::findOrFail($id);
        return $attribute->delete();
    }

    public function find(int $id)
    {
        return ProductAttribute::findOrFail($id);
    }
}