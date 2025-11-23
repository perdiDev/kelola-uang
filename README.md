# Aplikasi Keuangan

Aplikasi pencatatan pemasukan & pengeluaran berbasis Laravel + Filament.
Memiliki fitur laporan keuangan lengkap dengan export PDF & Excel.

---

## ✨ Fitur Utama

- CRUD Transaksi Keuangan
- Kategori Pemasukan & Pengeluaran
- Laporan berdasarkan:
  - Harian
  - Bulanan
  - Tahunan
  - Custom Range
- Export PDF
- Export Excel (tanpa dependency berat)
- Ringkasan Saldo Otomatis

---

## 🛠️ Tech Stack

| Teknologi | Keterangan |
|----------|------------|
| Laravel 12 | Backend utama |
| Filament v4 | Admin Panel & UI |
| MySQL / MariaDB | Database |
| Livewire | Interaktivitas UI |
| Tailwind | Styling |
| PHPSpreadsheet | Export Excel native |

---

## 🚀 Cara Install

```bash
git clone <repository-url>
cd <folder-project>

composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan optimize:clear

npm install
npm run dev

php artisan serve
