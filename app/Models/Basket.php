<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'thing_id',
        'count',
        'total',
        'size'
    ];

    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }

    public function sizeAndCount()
    {
        return $this->belongsTo(SizeAndCount::class);
    }
}
