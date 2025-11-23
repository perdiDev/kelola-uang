<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    // Mulai
    public static function getNavigationLabel(): string
    {
        return 'Kelola Role';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('role.view');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('role.create');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->check() && auth()->user()->hasPermission('role.edit');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check() && auth()->user()->hasPermission('role.delete');
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
