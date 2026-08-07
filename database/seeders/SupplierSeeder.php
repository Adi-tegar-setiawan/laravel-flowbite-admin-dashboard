<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::insert([
            [
                'name' => 'PT Sumber Makmur',
                'address' => 'Jakarta',
                'phone' => '081234567890',
                'email' => 'supplier1@mail.com',
            ],
            [
                'name' => 'CV Berkah Jaya',
                'address' => 'Bandung',
                'phone' => '081234567891',
                'email' => 'supplier2@mail.com',
            ],
            [
                'name' => 'PT Nusantara Digital',
                'address' => 'Surabaya',
                'phone' => '081234567892',
                'email' => 'supplier3@mail.com',
            ],
        ]);
    }
}
