<?php

namespace Modules\Product\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Database\Factories\ProductFactory;

/**
 * @Product
 * @property int|null $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string $status ['i', 'ii', 'iii']
 * @property int $stock_quantity
 * @method static filter(array $filter, string $class)
 */
class Product extends Model
{
    use HasFactory, Filterable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'stock_quantity'
    ];

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
