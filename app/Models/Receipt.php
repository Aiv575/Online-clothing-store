<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'address',
        'created_at',
        'total',
        'original_total',
        'promo_code_id',
    ];

    public function things()
    {
        return $this->belongsToMany(Thing::class, 'thing_receipts')
                    ->withPivot('count');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ReceiptItem::class);
    }

    public function returnRequests()
    {
        return $this->hasOne(ReturnRequest::class, 'receipt_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}
