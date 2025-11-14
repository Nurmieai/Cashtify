<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accounting extends Model
{
    use SoftDeletes;

    protected $table = 'accountings';
    protected $primaryKey = 'act_id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'act_created_at';
    const UPDATED_AT = 'act_updated_at';
    const DELETED_AT = 'act_deleted_at';

    protected $fillable = [
        'act_user_id',
        'act_exel_url',
        'act_period_from',
        'act_period_to',
        'act_total_sales',
        'act_total_items_sold',
        'act_created_by',
        'act_updated_by',
        'act_deleted_by',
        'act_sys_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'act_user_id', 'usr_id');
    }
}
