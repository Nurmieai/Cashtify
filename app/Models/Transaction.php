<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $table = 'transactions';
    protected $primaryKey = 'tst_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Custom Timestamps
    const CREATED_AT = 'tst_created_at';
    const UPDATED_AT = 'tst_updated_at';
    const DELETED_AT = 'tst_deleted_at';

    protected $fillable = [
        'tst_invoice',
        'tst_buyer_id',
        'tst_seller_id',

        'tst_total',
        'tst_subtotal',
        'tst_shipping_cost',

        'tst_payment_method',
        'tst_payment_status',
        'tst_payment_amount',
        'tst_payment_paid_at',
        'tst_expires_at',

        'tst_status',

        'tst_shipping_service',
        'tst_shipping_courier',
        'tst_tracking_code',

        'tst_notes',
        'tst_sys_note',

        // Audit Logs
        'tst_created_by',
        'tst_updated_by',
        'tst_deleted_by',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'tst_buyer_id', 'usr_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'tst_seller_id', 'usr_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'tst_item_transaction_id', 'tst_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipments::class, 'shp_transaction_id', 'tst_id');
    }

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

    public function setPaymentPending($amount, $method = 'manual', $createdBy = null)
    {
        $this->update([
            'tst_payment_method' => $method,
            'tst_payment_status' => 'pending',
            'tst_payment_amount' => $amount,
            'tst_payment_paid_at' => null,
            'tst_updated_by' => $createdBy,
        ]);
    }

    public function markPaymentSuccess($adminId)
    {
        $this->update([
            'tst_payment_amount' => $this->tst_total,
            'tst_payment_paid_at' => now(),
            'tst_payment_status' => 'paid',
            'tst_status'          => 'paid',
            'tst_updated_by'      => $adminId
        ]);
    }

    public function markPaymentCancelled($adminId)
    {
        $this->update([
            'tst_payment_status' => 'cancelled',
            'tst_status'         => 'cancelled',
            'tst_updated_by'     => $adminId
        ]);
    }

    public function canBeConfirmed()
    {
        return $this->tst_payment_status === 'paid';
    }
}
