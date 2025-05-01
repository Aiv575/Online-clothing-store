<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'return_request_id',
        'thing_id',
        'quantity',
        'price',
        'size',
        'reason',
        'image'
    ];

    // Связь с товаром
    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    // Связь с заказом
    public function return()
    {
        return $this->belongsTo(ReturnRequest::class);
    }
}
