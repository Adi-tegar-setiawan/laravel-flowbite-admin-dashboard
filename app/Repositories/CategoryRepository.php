<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::latest()->get();
    }

    public function find(int $id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(int $id, array $data)
    {
        $category = $this->find($id);

        $category->update($data);

        return $category;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }

    public function paginate(int $perPage = 10)
    {
        return Category::latest()->paginate($perPage);
    }

    public function search(?string $keyword = null, int $perPage = 10)
    {
        $query = Category::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }
}