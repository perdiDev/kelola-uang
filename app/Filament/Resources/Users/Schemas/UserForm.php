<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->label('Nama')
                ->required(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->unique(ignoreRecord: true)
                ->required(),

            CheckboxList::make('roles')
                ->label('Role')
                ->relationship('roles', 'label')
                ->columns(2)
                ->searchable()
                ->helperText('User dapat memiliki lebih dari satu role.')
                ->disabled(fn ($record) => $record?->id === auth()->id()),
        ]);
    }
}
