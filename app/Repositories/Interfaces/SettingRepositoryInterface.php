<?php

namespace App\Repositories\Interfaces;

interface SettingRepositoryInterface
{
    /**
     * Mengambil semua data setting dalam bentuk array key-value.
     */
    public function getAllAsKeyValue(): array;

    /**
     * Mengambil nilai setting berdasarkan key.
     */
    public function getByKey(string $key, ?string $default = null): ?string;

    /**
     * Menyimpan atau memperbarui setting berdasarkan key.
     */
    public function set(string $key, ?string $value): void;
}