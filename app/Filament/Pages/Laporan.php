<?php

namespace App\Filament\Pages;

use App\Models\Transaction;
use BackedEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class Laporan extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.laporan';

    protected static ?string $navigationLabel = 'Laporan Keuangan';

    public ?string $periode = 'bulanan';

    public ?string $start_date = null;

    public ?string $end_date = null;

    // ------- FILTER LOGIC -------
    protected function getTableQuery()
    {
        $query = Transaction::with('category')
            ->whereNull('deleted_at');  // soft delete safety

        if ($this->periode === 'harian') {
            $query->whereDate('transaction_date', today());
        } elseif ($this->periode === 'bulanan') {
            $query->whereMonth('transaction_date', now()->month)
                ->whereYear('transaction_date', now()->year);
        } elseif ($this->periode === 'tahunan') {
            $query->whereYear('transaction_date', now()->year);
        } elseif ($this->periode === 'custom' && $this->start_date && $this->end_date) {
            $query->whereBetween('transaction_date', [
                Carbon::parse($this->start_date),
                Carbon::parse($this->end_date),
            ]);
        }

        return $query->orderBy('transaction_date');
    }

    // ------- EXPORT LOGIC -------
    public function exportPDF()
    {
        $data = $this->getTableQuery()->get();

        return response()->streamDownload(function () use ($data) {
            echo Pdf::loadView('exports.laporan-pdf', compact('data'))->stream();
        }, 'laporan-keuangan.pdf');
    }

    public function exportExcel()
    {
        $file = \App\Exports\LaporanExport::generate(
            $this->start_date,
            $this->end_date
        );

        return response()->download($file)->deleteFileAfterSend();
    }

    // ------- TABLE UI -------
    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('exportPdf')
                    ->label('Export PDF')
                    ->color('danger')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn () => $this->exportPDF()),

                Action::make('exportExcel')
                    ->label('Export Excel')
                    ->color('success')
                    ->icon('heroicon-o-table-cells')
                    ->action(fn () => $this->exportExcel()),
            ])

            ->columns([
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->searchable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->limit(25),

                Tables\Columns\BadgeColumn::make('transaction_type')
                    ->label('Tipe')
                    ->colors([
                        'success' => 'income',
                        'danger' => 'expense',
                    ])
                    ->formatStateUsing(fn ($state) => ($state === 'income') ? 'Pemasukan' : 'Pengeluaran'
                    ),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Jumlah')
                    ->money('idr', true) // gunakan format IDR
                    ->sortable(),
            ])
            ->filters([])
            ->paginated(false); // laporan = tampil semua
    }
}
