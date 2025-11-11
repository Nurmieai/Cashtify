<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Blameable
{
    public function getBlameablePrefix()
{
    return 'usr';
}


    public static function bootBlameable()
{
    static::creating(function ($model) {
        $prefix = $model->getBlameablePrefix();
        $model->{$prefix . '_created_by'} = Auth::id();
        $model->{$prefix . '_updated_by'} = Auth::id();
    });

    static::updating(function ($model) {
        $prefix = $model->getBlameablePrefix();
        $model->{$prefix . '_updated_by'} = Auth::id();
    });

    static::deleting(function ($model) {
        $prefix = $model->getBlameablePrefix();
        if (Auth::check() && in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
            $model->{$prefix . '_deleted_by'} = Auth::id();
            $model->timestamps = false;
            $model->update();
        }
    });
}
}
