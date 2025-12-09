<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'firm_id',
        'purchase_price',
        'payment_method',
        'delivery_cn_volume',
        'delivery_cn_rate',
        'delivery_cn',
        'delivery_tj_volume',
        'delivery_tj_rate',
        'delivery_tj',
        'cost_per_unit',
        'box_qty',
        'received_box_qty',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(PurchaseDocument::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(PurchaseReceipt::class);
    }
}
