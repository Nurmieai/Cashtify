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

    // custom timestamps
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

        // ======= PAYMENT FIELDS LANGSUNG MASUK SINI =======
        'tst_payment_method',   // cash / transfer / dummy
        'tst_payment_status',   // pending / success / failed / expired / cancelled
        'tst_payment_amount',   // nominal
        'tst_payment_paid_at',  // waktu bayar

        'tst_status',           // workflow transaksi
        'tst_shipping_service',
        'tst_shipping_courier',
        'tst_tracking_code',
        'tst_notes',

        'tst_created_by',
        'tst_updated_by',
        'tst_deleted_by',
        'tst_sys_note',
    ];

    // =============== RELATIONSHIPS ===============

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

    // =============== PAYMENT HELPERS TANPA MODEL PAYMENT ===============

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
            'tst_payment_status' => 2, // paid
            'tst_status'          => 2, // transaksi dibayar
            'tst_updated_by'      => $adminId
        ]);
    }

    public function markPaymentCancelled($adminId)
    {
        $this->update([
            'tst_payment_status' => 3, // failed
            'tst_status'         => 6, // dibatalkan
            'tst_updated_by'     => $adminId
        ]);
    }

    public function canBeConfirmed()
    {
        return (int)$this->tst_payment_status === 2;
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
}
