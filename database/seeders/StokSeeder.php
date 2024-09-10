<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'stok_id' => 1,
                'supplier_id' => 1,
                'barang_id' => 1,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '11'),
                'stok_jumlah' => 1000
            ],
            [
                'stok_id' => 2,
                'supplier_id' => 2,
                'barang_id' => 5,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '12'),
                'stok_jumlah' => 500
            ],
            [
                'stok_id' => 3,
                'supplier_id' => 1,
                'barang_id' => 3,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '10'),
                'stok_jumlah' => 1200
            ],
            [
                'stok_id' => 4,
                'supplier_id' => 3,
                'barang_id' => 9,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '9'),
                'stok_jumlah' => 300
            ],
            [
                'stok_id' => 5,
                'supplier_id' => 2,
                'barang_id' => 14,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '13'),
                'stok_jumlah' => 50
            ],
            [
                'stok_id' => 6,
                'supplier_id' => 1,
                'barang_id' => 8,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '7'),
                'stok_jumlah' => 2000
            ],
            [
                'stok_id' => 7,
                'supplier_id' => 3,
                'barang_id' => 7,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '6'),
                'stok_jumlah' => 1500
            ],
            [
                'stok_id' => 8,
                'supplier_id' => 2,
                'barang_id' => 11,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '14'),
                'stok_jumlah' => 3000
            ],
            [
                'stok_id' => 9,
                'supplier_id' => 1,
                'barang_id' => 4,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '8'),
                'stok_jumlah' => 500
            ],
            [
                'stok_id' => 10,
                'supplier_id' => 3,
                'barang_id' => 2,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '11'),
                'stok_jumlah' => 1200
            ],
            [
                'stok_id' => 11,
                'supplier_id' => 2,
                'barang_id' => 12,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '5'),
                'stok_jumlah' => 700
            ],
            [
                'stok_id' => 12,
                'supplier_id' => 1,
                'barang_id' => 10,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '10'),
                'stok_jumlah' => 400
            ],
            [
                'stok_id' => 13,
                'supplier_id' => 3,
                'barang_id' => 13,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '8'),
                'stok_jumlah' => 800
            ],
            [
                'stok_id' => 14,
                'supplier_id' => 2,
                'barang_id' => 6,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '12'),
                'stok_jumlah' => 2500
            ],
            [
                'stok_id' => 15,
                'supplier_id' => 1,
                'barang_id' => 1,
                'user_id' => 2,
                'stok_tanggal' => Carbon::create('2024', '9', '13'),
                'stok_jumlah' => 3500
            ]
            
        ];

        DB::table('t_stok') -> insert($data);
    }
}
