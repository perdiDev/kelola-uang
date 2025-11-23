<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Nama Permission')
                    ->required()
                    ->placeholder('contoh: Lihat Role'),

                TextInput::make('slug')
                    ->label('Identifier (Slug)')
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->placeholder('permission.view')
                    ->helperText('Gunakan format: resource.action')
                    ->rule('regex:/^[a-z0-9]+(\.[a-z0-9]+)+$/')
                    ->validationMessages([
                        'regex' => 'Format slug harus seperti: resource.action',
                    ])
                    ->disabled(fn ($record) => $record !== null),
            ]);
    }
}
