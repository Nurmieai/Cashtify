<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'prd_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // sesuaikan nama timestamp yang di-rename
    const CREATED_AT = 'usr_created_at';
    const UPDATED_AT = 'usr_updated_at';
    const DELETED_AT = 'usr_deleted_at';

    protected $fillable = [
        'prd_name',
        'prd_description',
        'prd_status',
        'prd_price',
        'prd_card_url',
        'prd_created_by',
        'prd_updated_by',
        'prd_deleted_by',
        'usr_sys_note',
    ];

    // relasi ke user (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'prd_created_by', 'usr_id');
    }
}
