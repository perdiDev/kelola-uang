<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('roles.slug')
                    ->label('Role')
                    ->colors([
                        'success' => fn ($record) => $record->role?->slug === 'owner',
                        'primary' => fn ($record) => $record->role?->slug === 'user',
                    ]),
            ])

            ->recordActions([
                EditAction::make()
                    ->visible(fn ($record) =>
                        auth()->id() === $record->id
                    ),
                DeleteAction::make()
                    ->visible(fn ($record) => auth()->
                        user()?->hasPermission('user.delete')
                        && auth()->id() !== $record->id
                    ),
            ])

            ->checkIfRecordIsSelectableUsing(function ($record) {
                return $record->role?->slug !== 'owner' &&
                       $record->id !== auth()->id();
            });
    }
}
