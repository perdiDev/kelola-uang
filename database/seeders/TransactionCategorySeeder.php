<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // INCOME
            ['name' => 'Gaji Bulanan', 'transaction_type' => 'income'],
            ['name' => 'Bonus', 'transaction_type' => 'income'],
            ['name' => 'Komisi Penjualan', 'transaction_type' => 'income'],
            ['name' => 'Investasi', 'transaction_type' => 'income'],
            ['name' => 'Deposit Bank', 'transaction_type' => 'income'],
            ['name' => 'Bagi Hasil', 'transaction_type' => 'income'],
            ['name' => 'Cashback', 'transaction_type' => 'income'],
            ['name' => 'Sumbangan Masuk', 'transaction_type' => 'income'],
            ['name' => 'Pendapatan Produk', 'transaction_type' => 'income'],
            ['name' => 'Pendapatan Jasa', 'transaction_type' => 'income'],
            ['name' => 'Royalti', 'transaction_type' => 'income'],
            ['name' => 'Aset Terjual', 'transaction_type' => 'income'],
            ['name' => 'Pendapatan Event', 'transaction_type' => 'income'],

            // EXPENSE
            ['name' => 'Makan & Minum', 'transaction_type' => 'expense'],
            ['name' => 'Sewa Kantor', 'transaction_type' => 'expense'],
            ['name' => 'Internet', 'transaction_type' => 'expense'],
            ['name' => 'Gaji Karyawan', 'transaction_type' => 'expense'],
            ['name' => 'Iklan / Ads', 'transaction_type' => 'expense'],
            ['name' => 'Transportasi', 'transaction_type' => 'expense'],
            ['name' => 'Bahan Baku', 'transaction_type' => 'expense'],
            ['name' => 'Alat Tulis Kantor', 'transaction_type' => 'expense'],
            ['name' => 'Perbaikan', 'transaction_type' => 'expense'],
            ['name' => 'Langganan Software', 'transaction_type' => 'expense'],
            ['name' => 'Maintenance', 'transaction_type' => 'expense'],
            ['name' => 'Biaya Hosting', 'transaction_type' => 'expense'],
            ['name' => 'Pajak', 'transaction_type' => 'expense'],
            ['name' => 'Pembelian Aset', 'transaction_type' => 'expense'],
            ['name' => 'Donasi', 'transaction_type' => 'expense'],
            ['name' => 'Pengiriman Barang', 'transaction_type' => 'expense'],
            ['name' => 'Keamanan', 'transaction_type' => 'expense'],
            ['name' => 'Kebersihan', 'transaction_type' => 'expense'],
        ];

        foreach ($categories as &$category) {
            $category['created_at'] = now();
            $category['updated_at'] = now();
        }

        DB::table('transaction_categories')->insert($categories);
    }
}
