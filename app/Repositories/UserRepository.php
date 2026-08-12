<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function all()
    {
        return User::latest()->get();
    }

    public function paginate(int $perPage = 10)
    {
        return User::latest()->paginate($perPage);
    }

    /**
     * Pencarian pengguna berdasarkan keyword (Nama/Email) & Role.
     */
    public function search(?string $keyword = null, ?string $role = null, int $perPage = 10)
    {
        $query = User::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        return $query->latest()->paginate($perPage);
    }

    public function find(int $id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update(int $id, array $data)
    {
        $user = $this->find($id);

        $user->update($data);

        return $user;
    }

    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}