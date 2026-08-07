<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Membuat user baru.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $data['password'] = Hash::make($data['password']);

            return $this->userRepository->create($data);

        });
    }

    /**
     * Mengubah user.
     */
    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            return $this->userRepository->update($id, $data);

        });
    }

    /**
     * Menghapus user.
     */
    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            return $this->userRepository->delete($id);

        });
    }
}