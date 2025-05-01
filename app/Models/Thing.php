<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thing extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sizeAndCount()
    {
        return $this->hasMany(SizeAndCount::class);
    }

    public function returnModel()
    {
        return $this->hasMany(ReturnRequest::class, 'thing_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating'); // Вычисляем средний рейтинг всех отзывов
    }

}
