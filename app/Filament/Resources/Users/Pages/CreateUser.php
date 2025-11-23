<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions\Action;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('Tambah')
                ->submit('create'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl('index'); // Kembali ke halaman list
    }
}
