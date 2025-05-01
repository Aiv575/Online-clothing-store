<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'receipt_id',
        'total_refund',
        'status',
        'user_id',
        'updated_at',
    ];

    protected $table = 'return_requests';

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function thing()
    {
        return $this->belongsTo(Thing::class, 'thing_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(ReturnItem::class, 'return_request_id');
    }
}
