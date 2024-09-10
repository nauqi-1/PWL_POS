<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'barang_id' => 1, 
                'kategori_id' => 1,
                'barang_kode' => 'INDOFOOD001',
                'barang_nama' => 'Indomie Mie Goreng',
                'harga_beli' => 2200,
                'harga_jual' => 2500
            ], 
            [
                'barang_id' => 2, 
                'kategori_id' => 2,
                'barang_kode' => 'CIMORY001',
                'barang_nama' => 'Cimory Yogurt Plain',
                'harga_beli' => 4500,
                'harga_jual' => 5000
            ], 
            [
                'barang_id' => 3, 
                'kategori_id' => 1,
                'barang_kode' => 'INDOFOOD002',
                'barang_nama' => 'Indomie Mie Kari',
                'harga_beli' => 2200,
                'harga_jual' => 2500
            ],
            [
                'barang_id' => 4, 
                'kategori_id' => 3,
                'barang_kode' => 'FC001',
                'barang_nama' => 'FaberCastell Pensil 2Bx10',
                'harga_beli' => 4500,
                'harga_jual' => 5000
            ],
            [
                'barang_id' => 5, 
                'kategori_id' => 1,
                'barang_kode' => 'SASA001',
                'barang_nama' => 'Sasa GulaPasir 1kg',
                'harga_beli' => 12000,
                'harga_jual' => 13500
            ],
            [
                'barang_id' => 6, 
                'kategori_id' => 1,
                'barang_kode' => 'WINGS001',
                'barang_nama' => 'Mie Sedaap Goreng',
                'harga_beli' => 2300,
                'harga_jual' => 2600
            ],
            [
                'barang_id' => 7, 
                'kategori_id' => 2,
                'barang_kode' => 'ULTRA001',
                'barang_nama' => 'UltraMilk FullCream 1L',
                'harga_beli' => 15000,
                'harga_jual' => 16000
            ],
            [
                'barang_id' => 8, 
                'kategori_id' => 4,
                'barang_kode' => 'FALCON001',
                'barang_nama' => 'Falcon Pulpen 0.5mm',
                'harga_beli' => 2000,
                'harga_jual' => 2500
            ],
            [
                'barang_id' => 9, 
                'kategori_id' => 5,
                'barang_kode' => 'XIAOMI001',
                'barang_nama' => 'Xiaomi MiPowerbank 10000mAh',
                'harga_beli' => 150000,
                'harga_jual' => 165000
            ],
            [
                'barang_id' => 10, 
                'kategori_id' => 5,
                'barang_kode' => 'SAMSUNG001',
                'barang_nama' => 'Samsung USBCharger 15W',
                'harga_beli' => 75000,
                'harga_jual' => 80000
            ],
            [
                'barang_id' => 11, 
                'kategori_id' => 1,
                'barang_kode' => 'GARUDA001',
                'barang_nama' => 'Garuda KacangGoreng 250g',
                'harga_beli' => 15000,
                'harga_jual' => 17500
            ],
            [
                'barang_id' => 12, 
                'kategori_id' => 2,
                'barang_kode' => 'AQUA001',
                'barang_nama' => 'Aqua Galon 19L',
                'harga_beli' => 18000,
                'harga_jual' => 20000
            ],
            [
                'barang_id' => 13, 
                'kategori_id' => 3,
                'barang_kode' => 'COMBIPHAR001',
                'barang_nama' => 'OBH Combi 200ml',
                'harga_beli' => 8000,
                'harga_jual' => 10000
            ],
            [
                'barang_id' => 14, 
                'kategori_id' => 5,
                'barang_kode' => 'SHARP001',
                'barang_nama' => 'Sharp LEDTV 32Inch',
                'harga_beli' => 2000000,
                'harga_jual' => 2150000
            ],
            [
                'barang_id' => 15, 
                'kategori_id' => 3,
                'barang_kode' => 'SANMOL001',
                'barang_nama' => 'Sanmol Paracetamol Tablet',
                'harga_beli' => 2000,
                'harga_jual' => 2500
            ] 
        ];

        DB::table('m_barang') -> insert($data);
    }
}
