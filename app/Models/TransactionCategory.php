<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionCategory extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionCategoryFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = ["name", "transaction_type"];

    protected function casts()
    {
        return [
            "transaction_type" => TransactionType::class
        ];
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
