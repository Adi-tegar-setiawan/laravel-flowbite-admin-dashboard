<?php

namespace App\Services;

use App\Repositories\Interfaces\SettingRepositoryInterface;
use App\Services\ActivityLogService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    public function __construct(
        protected SettingRepositoryInterface $settingRepository,
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Mengambil semua pengaturan dalam bentuk array.
     */
    public function getSettings(): array
    {
        $settings = $this->settingRepository->getAllAsKeyValue();

        // Standard default values jika belum diset di DB
        return [
            'app_name' => $settings['app_name'] ?? 'Stockify',
            'app_logo' => $settings['app_logo'] ?? null,
            'company_name' => $settings['company_name'] ?? 'Stockify Warehouse',
            'company_email' => $settings['company_email'] ?? 'admin@stockify.com',
            'company_phone' => $settings['company_phone'] ?? '-',
        ];
    }

    /**
     * Memperbarui data pengaturan aplikasi.
     */
    public function updateSettings(array $data, ?UploadedFile $logoFile = null): void
    {
        // 1. Simpan/Update data teks
        if (isset($data['app_name'])) {
            $this->settingRepository->set('app_name', $data['app_name']);
        }

        if (isset($data['company_name'])) {
            $this->settingRepository->set('company_name', $data['company_name']);
        }

        if (isset($data['company_email'])) {
            $this->settingRepository->set('company_email', $data['company_email']);
        }

        if (isset($data['company_phone'])) {
            $this->settingRepository->set('company_phone', $data['company_phone']);
        }

        // 2. Handle upload logo baru jika ada
        if ($logoFile) {
            $oldLogo = $this->settingRepository->getByKey('app_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $logoPath = $logoFile->store('settings', 'public');
            $this->settingRepository->set('app_logo', $logoPath);
        }

        // 3. Catat di Activity Log
        $this->activityLogService->log(
            action: 'update',
            description: 'Admin memperbarui Pengaturan Umum Aplikasi.',
            properties: [
                'updated_fields' => array_keys($data),
            ]
        );
    }
}