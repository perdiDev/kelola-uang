<?php

namespace Database\Factories;

use App\Models\TransactionCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar item realistis berdasarkan kategori
        $items = [
            // Produk (kategori 9)
            ['category' => 9, 'items' => [
                'Kopi Arabica 250gr',
                'Susu UHT Full Cream 1L',
                'Roti Tawar Gandum',
                'Snack Keripik Singkong',
                'Es Krim Vanilla 1L',
                'Jus Jeruk Segar 500ml',
                'Nasi Goreng Special',
                'Mie Goreng Seafood',
                'Ayam Geprek Pedas',
                'Sate Ayam 10 Tusuk',
            ], 'price_range' => [15000, 85000]],
            
            // Jasa (kategori 10)
            ['category' => 10, 'items' => [
                'Jasa Desain Logo',
                'Jasa Fotografi Event',
                'Jasa Konsultasi Bisnis',
                'Jasa Pembuatan Website',
                'Jasa Live Streaming',
                'Jasa Video Editing',
                'Jasa Social Media Marketing',
                'Jasa Content Writing',
                'Jasa Maintenance Komputer',
                'Jasa Training Online',
            ], 'price_range' => [150000, 2500000]],
            
            // Bahan Baku (kategori 20)
            ['category' => 20, 'items' => [
                'Tepung Terigu Premium 1kg',
                'Gula Pasir 1kg',
                'Mentega Butter 500gr',
                'Cokelat Bubuk 250gr',
                'Minyak Goreng 5L',
                'Bawang Putih 1kg',
                'Cabe Rawit 500gr',
                'Daging Ayam Fillet 1kg',
                'Ikan Salmon Fresh 500gr',
                'Sayuran Organik Mix',
            ], 'price_range' => [20000, 180000]],
            
            // ATK (kategori 21)
            ['category' => 21, 'items' => [
                'Kertas A4 80gsm 1 Rim',
                'Pulpen Gel Hitam',
                'Spidol Whiteboard Set',
                'Stapler Heavy Duty',
                'Paper Clip Box',
                'Isolasi Lakban Bening',
                'Penghapus Whiteboard',
                'Gunting Stainless Steel',
                'Lem Stick Glue',
                'Map Plastik Warna',
            ], 'price_range' => [5000, 65000]],
        ];

        // Pilih kategori secara random
        $selectedCategory = fake()->randomElement($items);
        $categoryId = $selectedCategory['category'];
        $itemName = fake()->randomElement($selectedCategory['items']);
        $priceRange = $selectedCategory['price_range'];

        return [
            'name' => $itemName,
            'transaction_category_id' => $categoryId,
            'item_price' => fake()->numberBetween($priceRange[0], $priceRange[1]),
        ];
    }

    /**
     * Item untuk kategori produk makanan/minuman
     */
    public function product(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_category_id' => 9,
            'name' => fake()->randomElement([
                'Kopi Latte Premium',
                'Cappuccino Special',
                'Teh Tarik Malaysia',
                'Smoothie Bowl Berry',
                'Sandwich Tuna Mayo',
                'Burger Beef Cheese',
                'Pizza Margherita Personal',
                'Pasta Carbonara',
            ]),
            'item_price' => fake()->numberBetween(25000, 75000),
        ]);
    }

    /**
     * Item untuk kategori jasa
     */
    public function service(): static
    {
        return $this->state(fn (array $attributes) => [
            'transaction_category_id' => 10,
            'name' => fake()->randomElement([
                'Jasa Konsultasi Digital Marketing',
                'Jasa Pembuatan Aplikasi Mobile',
                'Jasa SEO Optimization',
                'Jasa Branding & Identity',
                'Jasa Copywriting Professional',
                'Jasa Video Production',
            ]),
            'item_price' => fake()->numberBetween(500000, 5000000),
        ]);
    }
}
