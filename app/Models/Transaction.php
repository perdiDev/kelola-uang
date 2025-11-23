<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_id',
        'transaction_category_id',
        'transaction_type',
        'transaction_date',
        'description',
        'amount',
        'quantity',
    ];

    protected function casts(): array
    {
        return [
            'transaction_date' => 'datetime',
            'transaction_type' => 'string',
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function category()
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }

    // total_amount otomatis — tidak disimpan di DB
    public function getTotalAmountAttribute()
    {
        return $this->amount * $this->quantity;
    }
}
