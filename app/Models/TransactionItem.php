<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionItem extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'tst_item_id';

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
}
