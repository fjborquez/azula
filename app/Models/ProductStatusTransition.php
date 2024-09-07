<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductStatusTransition extends Model
{
    use HasFactory;

    protected $table = 'product_status_transitions';

    protected $fillable = [
        'inventory_id',
        'product_status_id',
        'is_active',
        'observations',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function productStatus(): BelongsTo
    {
        return $this->belongsTo(ProductStatus::class);
    }
}
