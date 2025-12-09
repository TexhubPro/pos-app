<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\FirmPayment;

class Firm extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'debt',
        'comment',
    ];

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(FirmPayment::class);
    }
}
