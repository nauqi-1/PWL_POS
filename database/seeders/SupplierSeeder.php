<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            [
                'supplier_id' => 1,
                'supplier_kode' => 'MLG001',
                'supplier_nama' => 'Sigma Grosir Malang',
                'supplier_alamat' => 'Jalan Sakura Petal 20, Malang'
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'MLG002',
                'supplier_nama' => ' Skibidi Wholesale Malang',
                'supplier_alamat' => 'Jalan Fanum 35, Malang'
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SBY001',
                'supplier_nama' => 'Costco Surabaya',
                'supplier_alamat' => 'Jalan Mew Mog 11, Surabaya'
            ],


        ];

        DB::table('m_supplier') -> insert($data);
    }
}
