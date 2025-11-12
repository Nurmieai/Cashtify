<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $primaryKey = 'prd_id';
    public $timestamps = true;

    protected const DELETED_AT = 'usr_deleted_at';

    protected $fillable = [
        'prd_name',
        'prd_description',
        'prd_status',
        'prd_price',
        'prd_created_by',
        'prd_updated_by',
        'prd_deleted_by',
        'usr_sys_note',
    ];

    protected $dates = ['usr_created_at', 'usr_updated_at', 'usr_deleted_at'];
}
