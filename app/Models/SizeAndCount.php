<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SizeAndCount extends Model
{
    protected $fillable = [
        'thing_id',
        'size',
        'count'
    ];

    // Связь с товаром
    public function thing()
    {
        return $this->belongsTo(Thing::class);
    }
    
}
