<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $table = 'carts';
    protected $primaryKey = 'crs_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'crs_created_at';
    const UPDATED_AT = 'crs_updated_at';
    const DELETED_AT = 'crs_deleted_at';

    protected $dates = ['crs_time'];

    protected $fillable = [
        'crs_time',
        'crs_user_id',
        'crs_product_id',
        'crs_created_by',
        'crs_updated_by',
        'crs_deleted_by',
        'crs_sys_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'crs_user_id', 'usr_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'crs_product_id', 'prd_id');
    }
}
