<?php

namespace App\Filament\Resources\Roles\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Nama Role')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->colors([
                        'success' => fn ($record) => $record->slug ==='owner',
                        'primary' => fn ($record) => $record->slug !=='owner',
                    ])
                    ->searchable()
                    ->sortable()
                    ->badge(),
            ])
            ->recordActions([
                EditAction::make()
                    ->visible(fn () => auth()->user()?->hasPermission('role.edit')
                    ),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->user()?->hasPermission('role.delete')
                    && $record->slug !== 'owner' 
                    ),
                ]);
    }
}
