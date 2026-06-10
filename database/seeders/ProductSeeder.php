<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Product;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            throw new Exception('No company found');
        }

        Product::insert([
            [
                'company_id' => $company->id,
                'name' => 'Dell Laptop',
                'sku' => 'DELL-001',
                'barcode' => '100000001',
                'price' => 55000,
                'stock' => 50,
                'description' => 'Dell Inspiron Laptop',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company->id,
                'name' => 'HP Laptop',
                'sku' => 'HP-001',
                'barcode' => '100000002',
                'price' => 60000,
                'stock' => 40,
                'description' => 'HP Pavilion Laptop',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company->id,
                'name' => 'Wireless Mouse',
                'sku' => 'MOUSE-001',
                'barcode' => '100000003',
                'price' => 800,
                'stock' => 200,
                'description' => 'Wireless Optical Mouse',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company->id,
                'name' => 'Mechanical Keyboard',
                'sku' => 'KEY-001',
                'barcode' => '100000004',
                'price' => 2500,
                'stock' => 100,
                'description' => 'RGB Mechanical Keyboard',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_id' => $company->id,
                'name' => '27 Inch Monitor',
                'sku' => 'MON-001',
                'barcode' => '100000005',
                'price' => 15000,
                'stock' => 30,
                'description' => 'Full HD LED Monitor',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
