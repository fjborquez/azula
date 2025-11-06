<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';

    public $oldInventory;
    public $mergedId;

    protected $fillable = [
        'house_id',
        'house_description',
        'quantity',
        'uom_id',
        'uom_abbreviation',
        'purchase_date',
        'expiration_date',
        'catalog_id',
        'catalog_description',
        'brand_id',
        'brand_name',
        'category_id',
        'category_name',
    ];

    protected $appends = ['mergedId', 'oldInventory'];

    public function productStatus(): BelongsToMany
    {
        return $this->belongsToMany(ProductStatus::class, 'product_status_transitions', 'inventory_id', 'product_status_id')
            ->withPivot(['observations', 'is_active']);
    }

    public function getMergedIdAttribute()
    {
        return $this->mergedId;
    }

    public function getOldInventoryAttribute()
    {
        return $this->oldInventory;
    }
}
