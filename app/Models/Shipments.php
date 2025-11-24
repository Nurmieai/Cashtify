<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipments extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipments';
    protected $primaryKey = 'shp_id';

    protected $fillable = [
        'shp_transaction_id',
        'shp_status',
        'shp_courier',
        'shp_service',
        'shp_tracking_code',
        'shp_sent_at',
        'shp_delivered_at',
        'shp_notes',

        'shp_address',
        'shp_latitude',
        'shp_longitude',

        'shp_created_by',
        'shp_updated_by',
        'shp_deleted_by',
        'shp_sys_note',
    ];


    // Custom timestamps
    const CREATED_AT = 'shp_created_at';
    const UPDATED_AT = 'shp_updated_at';
    const DELETED_AT = 'shp_deleted_at';
    protected $dates = ['shp_deleted_at', 'shp_sent_at', 'shp_delivered_at'];

    // ----------------- RELATIONSHIPS -----------------

    // Shipment belongs to a transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'shp_transaction_id', 'tst_id');
    }

    // Audit fields: created/updated/deleted by user
    public function creator()
    {
        return $this->belongsTo(User::class, 'shp_created_by', 'usr_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'shp_updated_by', 'usr_id');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'shp_deleted_by', 'usr_id');
    }
}
