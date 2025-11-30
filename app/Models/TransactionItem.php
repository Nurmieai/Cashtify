<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model
{
    use SoftDeletes;

    protected $table = 'transaction_items';
    protected $primaryKey = 'tst_item_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'tst_item_created_at';
    const UPDATED_AT = 'tst_item_updated_at';
    const DELETED_AT = 'tst_item_deleted_at';

    protected $fillable = [
        'tst_item_transaction_id',
        'tst_item_product_id',
        'tst_item_product_name',
        'tst_item_quantity',
        'tst_item_price',
        'tst_item_subtotal',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tst_item_transaction_id', 'tst_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'tst_item_product_id', 'prd_id');
    }
}
