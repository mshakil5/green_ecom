<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSell extends Model
{
    use HasFactory;

    public function FlashSellDetails()
    {
        return $this->hasMany(FlashSellDetails::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeDateActive($query, $date = null)
    {
        $date = $date ?? now();
        return $query->whereDate('start_date', '<=', $date)
                    ->whereDate('end_date', '>=', $date);
    }

}
