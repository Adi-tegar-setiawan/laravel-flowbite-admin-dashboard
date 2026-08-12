<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserService $userService,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Menampilkan daftar user.
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $role = $request->get('role');

        $users = $this->userService->searchUsers($keyword, $role, 10);

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

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:Admin,Manajer Gudang,Staff Gudang',
            ],
        ]);

        $user = $this->userService->create($validated);

        // Activity Log
        $this->activityLogService->log(
            action: 'CREATE',
            description: 'Menambahkan user baru "' . $user->name . '"',
            subjectType: 'User',
            subjectId: $user->id,
            properties: [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        );

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

            'password' => [
                'nullable',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:Admin,Manajer Gudang,Staff Gudang',
            ],
        ]);

        // Ambil data sebelum diubah
        $user = $this->userRepository->find($id);

        $oldData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $this->userService->update($id, $validated);

        // Activity Log
        $this->activityLogService->log(
            action: 'UPDATE',
            description: 'Mengubah user "' . $user->name . '"',
            subjectType: 'User',
            subjectId: $user->id,
            properties: [
                'old' => $oldData,
                'new' => [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'role' => $validated['role'],
                ],
            ]
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus user.
     */
    public function destroy(int $id)
    {
        // Ambil data sebelum dihapus
        $user = $this->userRepository->find($id);

        $userData = [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];

        $this->userService->delete($id);

        // Activity Log
        $this->activityLogService->log(
            action: 'DELETE',
            description: 'Menghapus user "' . $user->name . '"',
            subjectType: 'User',
            subjectId: $user->id,
            properties: $userData
        );

        return redirect()
            ->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}