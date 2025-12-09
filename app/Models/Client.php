<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'comment',
        'debt',
    ];

    public function payments()
    {
        return $this->hasMany(ClientPayment::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
