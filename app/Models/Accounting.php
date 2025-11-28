<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Accounting extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'act_id';

    protected $fillable = [
        'act_user_id',
        'act_excel_url',
        'act_period_from',
        'act_period_to',
        'act_total_transactions',
        'act_transaction_ids',
        'act_total_sales',
        'act_total_items_sold',
        'act_total_payment_amount',
        'act_total_shipping_cost',
        'act_total_income',
        'act_total_expense',
        'act_created_by',
        'act_updated_by',
        'act_deleted_by',
        'act_sys_note',
    ];

    protected $casts = [
        'act_transaction_ids' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'act_user_id', 'usr_id');
    }
}
