<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'code',
        'discount',
        'type',
        'expires_at',
        'is_active',
    ];

    protected $dates = ['expires_at'];

    // Проверка срока действия промокода
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at < now();
    }

    // Проверка активности промокода
    public function isValid()
    {
        return $this->is_active && !$this->isExpired();
    }

    // Применение промокода
    public function applyDiscount($total)
    {
        if (!$this->isValid()) {
            return $total; // Если промокод невалиден, возвращаем цену без изменений
        }

        if ($this->type === 'percentage') {
            return $total * (1 - $this->discount / 100);
        }

        return max(0, $total - $this->discount); // Учитываем, что цена не может быть меньше 0
    }
}
