<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Shipments;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transactions';
    protected $primaryKey = 'tst_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Custom timestamps
    const CREATED_AT = 'tst_created_at';
    const UPDATED_AT = 'tst_updated_at';
    const DELETED_AT = 'tst_deleted_at';

    protected $fillable = [
        'tst_invoice',
        'tst_buyer_id',
        'tst_seller_id',
        'tst_total',
        'tst_subtotal',
        'tst_discount',
        'tst_shipping_cost',
        'tst_payment_method',
        'tst_payment_status',
        'tst_status',
        'tst_shipping_service',
        'tst_shipping_courier',
        'tst_tracking_code',
        'tst_notes',
        'tst_created_by',
        'tst_updated_by',
        'tst_deleted_by',
        'tst_sys_note',
    ];

    // ----------------- RELATIONSHIPS -----------------

    // Buyer dan Seller
    public function buyer()
    {
        return $this->belongsTo(User::class, 'tst_buyer_id', 'usr_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'tst_seller_id', 'usr_id');
    }

    // Transaction Items
    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'tst_item_transaction_id', 'tst_id');
    }

    // Shipment terkait
    public function shipments()
    {
        return $this->hasOne(Shipments::class, 'shp_transaction_id', 'tst_id');
    }

    // Audit relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'tst_created_by', 'usr_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'tst_updated_by', 'usr_id');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'tst_deleted_by', 'usr_id');
    }
}
