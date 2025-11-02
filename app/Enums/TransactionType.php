<?php

namespace App\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum TransactionType: string implements HasLabel, HasColor
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function getLabel(): string|Htmlable|null
    {
        return __("enums.transaction_type.{$this->value}");
    }

    public function getColor(): string|array|null
    {
        return match ( $this ) {
            self::INCOME => Color::Green,
            self::EXPENSE =>Color::Red,
        };
    }
}
