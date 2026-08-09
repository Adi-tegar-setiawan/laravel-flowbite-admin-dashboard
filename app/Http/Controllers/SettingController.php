<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {
    }

    /**
     * Menampilkan halaman formulir pengaturan aplikasi.
     */
    public function index(): View
    {
        $settings = $this->settingService->getSettings();

        return view('settings.index', compact('settings'));
    }

    /**
     * Memperbarui pengaturan umum aplikasi.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'company_name' => ['required', 'string', 'max:150'],
            'company_email' => ['nullable', 'email', 'max:100'],
            'company_phone' => ['nullable', 'string', 'max:30'],
            'app_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ]);

        $logoFile = $request->file('app_logo');

        $this->settingService->updateSettings($validated, $logoFile);

        return redirect()
            ->route('settings.index')
            ->with('success', 'Pengaturan aplikasi berhasil diperbarui.');
    }
}