<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserService $userService
    ) {
    }

    /**
     * Menampilkan daftar user.
     */
    public function index()
    {
        $users = $this->userRepository->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role' => ['required', 'in:Admin,Manajer Gudang,Staff Gudang'],
        ]);

        $this->userService->create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(int $id)
    {
        $user = $this->userRepository->find($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Mengupdate user.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],

            'password' => ['nullable', 'min:8', 'confirmed'],

            'role' => [
                'required',
                'in:Admin,Manajer Gudang,Staff Gudang',
            ],
        ]);

        $this->userService->update($id, $validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus user.
     */
    public function destroy(int $id)
    {
        $this->userService->delete($id);

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}