<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartItem extends Model
{
    use SoftDeletes;

    protected $table = 'cart_items';
    protected $primaryKey = 'crs_item_id';

    const CREATED_AT = 'crs_item_created_at';
    const UPDATED_AT = 'crs_item_updated_at';
    const DELETED_AT = 'crs_item_deleted_at';

    protected $fillable = [
        'crs_item_cart_id',
        'crs_item_product_id',
        'crs_item_quantity',
        'crs_item_price',
        'crs_item_subtotal',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'crs_item_cart_id', 'crs_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'crs_item_product_id', 'prd_id');
    }
}
