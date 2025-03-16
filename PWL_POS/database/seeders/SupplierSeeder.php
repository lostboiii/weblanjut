<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SupplierModel;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data for suppliers
        $suppliers = [
            [
                'supplier_nama' => 'Supplier A',
                'supplier_kontak' => '123456789',
                'supplier_alamat' => 'Address A',
            ],
            [
                'supplier_nama' => 'Supplier B',
                'supplier_kontak' => '987654321',
                'supplier_alamat' => 'Address B',
            ],
            [
                'supplier_nama' => 'Supplier C',
                'supplier_kontak' => '456789123',
                'supplier_alamat' => 'Address C',
            ],
        ];

    
        foreach ($suppliers as $supplier) {
            SupplierModel::create($supplier);
        }
    }
}
