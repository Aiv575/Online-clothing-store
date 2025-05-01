<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'receipt_id',
        'thing_id',
        'quantity',
        'price',
        'size'
    ];

    // Связь с товаром
    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    // Связь с заказом
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

}
