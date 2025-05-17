<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    public function things()
    {
        return $this->hasMany(Thing::class);
    }

    public function getRandomThingImageAttribute()
    {
        // Получаем случайный товар из категории с изображением
        $randomThing = $this->things()
            ->whereHas('imageAttachment')
            ->inRandomOrder()
            ->first();

        return $randomThing ? $randomThing->imageAttachment->url() : null;
    }
}
