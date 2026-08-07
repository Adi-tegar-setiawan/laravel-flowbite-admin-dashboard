<?php

namespace App\Repositories;

use App\Models\Supplier;
use App\Repositories\Interfaces\SupplierRepositoryInterface;

class SupplierRepository implements SupplierRepositoryInterface
{
    public function all()
    {
        return Supplier::latest()->get();
    }

    public function find(int $id)
    {
        return Supplier::findOrFail($id);
    }

    public function create(array $data)
    {
        return Supplier::create($data);
    }

    public function update(int $id, array $data)
    {
        $supplier = $this->find($id);

        $supplier->update($data);

        return $supplier;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}