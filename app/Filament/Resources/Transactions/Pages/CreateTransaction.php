<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    public function mount(): void
    {
        parent::mount();

        $type = request()->get('type');

        if ($type === 'income' || $type === 'expense') {
            $this->form->fill([
                'transaction_type' => $type,
            ]);
        }
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['transaction_type'] = request()->get('type') ?? 'income';
    //     return $data;
    // }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($data); // ⬅ debug di sini

    //     return $data;
    // }
}
