<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Tentukan apakah ini income atau expense
        $transactionType = fake()->randomElement(['income', 'expense']);
        
        // Pilih kategori berdasarkan tipe transaksi
        if ($transactionType === 'income') {
            // Kategori income: 1-13
            $categoryIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13];
        } else {
            // Kategori expense: 14-31
            $categoryIds = [14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31];
        }
        
        $categoryId = fake()->randomElement($categoryIds);
        
        // Ambil atau buat item untuk kategori ini
        $item = Item::where('transaction_category_id', $categoryId)->inRandomOrder()->first()
            ?? Item::factory()->create(['transaction_category_id' => $categoryId]);

        // Tentukan quantity yang realistis berdasarkan kategori
        $quantity = match ($categoryId) {
            // Gaji, Bonus, Komisi - biasanya 1
            1, 2, 3 => 1,
            // Produk makanan/minuman - bisa banyak
            9, 14 => fake()->numberBetween(1, 50),
            // Jasa - biasanya 1-5
            10, 17, 18, 22, 23 => fake()->numberBetween(1, 5),
            // Bahan baku, ATK - medium quantity
            20, 21 => fake()->numberBetween(1, 20),
            // Default
            default => fake()->numberBetween(1, 10),
        };

        // Deskripsi realistis berdasarkan tipe dan kategori
        $descriptions = $this->getRealisticDescription($transactionType, $categoryId);

        return [
            'item_id' => $item->id,
            'transaction_category_id' => $categoryId,
            'transaction_type' => $transactionType,
            'transaction_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'description' => fake()->randomElement($descriptions),
            'amount' => $item->item_price,
            'quantity' => $quantity,
        ];
    }

    /**
     * Dapatkan deskripsi realistis berdasarkan kategori
     */
    private function getRealisticDescription(string $type, int $categoryId): array
    {
        $incomeDescriptions = [
            1 => ['Gaji bulan ini', 'Pembayaran gaji rutin', 'Transfer gaji'],
            2 => ['Bonus kinerja', 'Bonus tahun baru', 'Bonus target tercapai', 'Bonus penjualan'],
            3 => ['Komisi penjualan minggu ini', 'Komisi closing deal', 'Komisi referral'],
            9 => ['Penjualan produk ke customer', 'Order online', 'Pembayaran dari toko', 'Pelanggan membeli'],
            10 => ['Pembayaran jasa', 'Fee konsultasi', 'Pelunasan project', 'DP project'],
            11 => ['Royalti bulanan', 'Pendapatan hak cipta'],
            13 => ['Pemasukan event', 'Tiket terjual', 'Sponsorship event'],
        ];

        $expenseDescriptions = [
            14 => ['Belanja makan siang', 'Makan malam tim', 'Snack kantor', 'Catering meeting'],
            15 => ['Pembayaran sewa kantor bulan ini', 'Sewa bulanan', 'Biaya sewa'],
            16 => ['Tagihan internet bulan ini', 'Langganan internet', 'Biaya wifi'],
            17 => ['Gaji karyawan bulan ini', 'Transfer gaji staff', 'Payroll bulanan'],
            18 => ['Budget iklan Facebook', 'Biaya ads Google', 'Promosi Instagram', 'Boost post'],
            19 => ['Bensin kendaraan', 'Ongkos ojol', 'Parkir', 'Tol ke client'],
            20 => ['Pembelian bahan baku', 'Stok bahan produksi', 'Restock material'],
            21 => ['Pembelian ATK', 'Alat tulis kantor', 'Kertas dan tinta printer'],
            22 => ['Perbaikan AC', 'Service komputer', 'Maintenance peralatan'],
            23 => ['Langganan software', 'Subscription bulanan', 'License software'],
            26 => ['Pembayaran pajak', 'Pajak penghasilan', 'PPN'],
            28 => ['Donasi sosial', 'Sumbangan amal', 'Bantuan kemanusiaan'],
            29 => ['Ongkir pengiriman', 'Biaya kurir', 'Ekspedisi barang'],
        ];

        if ($type === 'income') {
            return $incomeDescriptions[$categoryId] ?? ['Pendapatan', 'Pemasukan'];
        } else {
            return $expenseDescriptions[$categoryId] ?? ['Pengeluaran', 'Biaya operasional'];
        }
    }

    /**
     * Transaksi bertipe income
     */
    public function income(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'income',
            'transaction_category_id' => fake()->randomElement([1, 2, 3, 9, 10]),
        ]);
    }

    /**
     * Transaksi bertipe expense
     */
    public function expense(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_type' => 'expense',
            'transaction_category_id' => fake()->randomElement([14, 15, 16, 17, 18, 19, 20, 21]),
        ]);
    }

    /**
     * Transaksi untuk bulan ini
     */
    public function thisMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_date' => fake()->dateTimeBetween('first day of this month', 'now'),
        ]);
    }

    /**
     * Transaksi untuk hari ini
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_date' => now(),
        ]);
    }

    /**
     * Transaksi dengan jumlah besar
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantity' => fake()->numberBetween(20, 100),
        ]);
    }
}
