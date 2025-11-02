<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TransactionType: string implements HasLabel
{
    case INCOME = 'pemasukan';
    case EXPENSE = 'pengeluaran';

    public function getLabel(): string|Htmlable|null
    {
        return $this->name;
    }
}
