<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductStatus extends Model
{
    use HasFactory;

    protected $table = 'product_status';

    protected $fillable = [
        'description',
        'is_final_phase',
    ];

    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventory::class, 'product_status_transitions', 'product_status_id', 'inventory_id');
    }
}
