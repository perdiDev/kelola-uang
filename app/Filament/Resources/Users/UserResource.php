<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function getNavigationLabel(): string
    {
        return 'Kelola User';
    }

    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('user.view');
    }

    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->hasPermission('user.create');
    }

    public static function canEdit(Model $record): bool
    {
        // return auth()->check() && auth()->user()->hasPermission('user.edit');
        return auth()->id() === $record->id
        || auth()->user()->hasPermission('user.edit');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->check()
            && auth()->user()->hasPermission('user.delete')
            && auth()->id() !== $record->id;
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
