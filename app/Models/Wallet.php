<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    private const MAX_VALUE = 999999.999;

    protected $fillable = [
        'user_id',
        'asset_id',
        'value'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function validateIncreasedValue(float $newValue)
    {
        return $this->value + $newValue <= self::MAX_VALUE;
    }

    public function validateDecreasedValue(float $newValue)
    {
        return $this->value - $newValue >= 0;
    }

    public function validateConvertedValue(Conversion $conversion, float $value)
    {
        if ($value * $conversion->ratio + $this->value > self::MAX_VALUE) {
            return false;
        }
        return true;
    }
}
