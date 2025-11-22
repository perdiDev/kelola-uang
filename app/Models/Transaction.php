<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ["item_id", "transaction_date", "description", "amount", "quantity"];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime'
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
