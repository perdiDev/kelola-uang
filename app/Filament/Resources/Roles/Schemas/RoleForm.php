<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\CheckboxList;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('slug')
                    ->label('Identifier (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('contoh: admin')
                    ->helperText('Jadi patokan akses sistem.')
                    ->disabled(fn ($record) => $record !== null), // jangan ubah slug kalo sudah ada

                TextInput::make('label')
                    ->label('Nama Role')
                    ->required()
                    ->placeholder('contoh: Administrator'),

                CheckboxList::make('permissions')
                    ->label('Izin Akses')
                    ->relationship('permissions', 'label')
                    ->columns(2)
                    ->searchable()
                    ->disabled(fn ($record) => $record?->slug === 'owner')
                    ->helperText('Centang fitur yang diizinkan untuk role ini.'),
            ]);
    }
}
