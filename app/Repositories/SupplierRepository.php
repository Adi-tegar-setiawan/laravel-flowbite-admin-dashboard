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

    public function search(?string $keyword = null, int $perPage = 10)
    {
        $query = Supplier::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    public function paginate(int $perPage = 10)
    {
        return Supplier::latest()->paginate($perPage);
    }
}