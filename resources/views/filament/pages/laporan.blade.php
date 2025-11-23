<x-filament::page>
    <div class="flex gap-4 mb-6">

        <x-filament::input.wrapper>
            <x-filament::input.select wire:model.defer="periode">
                <option value="harian">Harian</option>
                <option value="bulanan">Bulanan</option>
                <option value="tahunan">Tahunan</option>
                <option value="custom">Custom Range</option>
            </x-filament::input.select>
        </x-filament::input.wrapper>

        @if ($periode === 'custom')
            <input type="date" wire:model="start_date" class="input" />
            <input type="date" wire:model="end_date" class="input" />
        @endif

        <x-filament::button wire:click="$refresh">
            Tampilkan
        </x-filament::button>
    </div>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <x-filament::card>
            <p class="text-gray-500 text-sm">Total Pemasukan</p>
            <h3 class="font-bold text-xl">
                Rp {{ number_format(
                    $this->getTableQuery()
                        ->where('transaction_type', 'income')
                        ->get()
                        ->sum(fn ($t) => $t->total_amount)
                ) }}
            </h3>
        </x-filament::card>

        <x-filament::card>
            <p class="text-gray-500 text-sm">Total Pengeluaran</p>
            <h3 class="font-bold text-xl">
                Rp {{ number_format(
                    $this->getTableQuery()
                        ->where('transaction_type', 'expense')
                        ->get()
                        ->sum(fn ($t) => $t->total_amount)
                ) }}
            </h3>
        </x-filament::card>

        <x-filament::card>
            <p class="text-gray-500 text-sm">Saldo</p>
            <h3 class="font-bold text-xl">
                Rp {{
                    number_format(
                        $this->getTableQuery()
                            ->get()
                            ->sum(fn ($t) =>
                                $t->transaction_type === 'income'
                                    ? $t->total_amount
                                    : -$t->total_amount
                            )
                    )
                }}
            </h3>
        </x-filament::card>
    </div>

    {{ $this->table }}
</x-filament::page>
