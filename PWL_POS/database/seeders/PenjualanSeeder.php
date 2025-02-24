<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'penjualan_id' => 1,
                'user_id' => 3,
                'pembeli' => 'sumanto',
                'penjualan_kode' => 'P001',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 2,
                'user_id' => 3,
                'pembeli' => 'siti',
                'penjualan_kode' => 'P002',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 3,
                'user_id' => 3,
                'pembeli' => 'joko',
                'penjualan_kode' => 'P003',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 4,
                'user_id' => 3,
                'pembeli' => 'susi',
                'penjualan_kode' => 'P004',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 5,
                'user_id' => 3,
                'pembeli' => 'budi',
                'penjualan_kode' => 'P005',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 6,
                'user_id' => 3,
                'pembeli' => 'sri',
                'penjualan_kode' => 'P006',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 7,
                'user_id' => 3,
                'pembeli' => 'agus',
                'penjualan_kode' => 'P007',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 8,
                'user_id' => 3,
                'pembeli' => 'wati',
                'penjualan_kode' => 'P008',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 9,
                'user_id' => 3,
                'pembeli' => 'yati',
                'penjualan_kode' => 'P009',
                'tanggal_penjualan' => now(),
            ],
            [
                'penjualan_id' => 10,
                'user_id' => 3,
                'pembeli' => 'tati',
                'penjualan_kode' => 'P010',
                'tanggal_penjualan' => now(),
            ],
        ];
        DB::table('t_penjualan')->insert($data);
    }
}
