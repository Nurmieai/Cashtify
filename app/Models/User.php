<?php

namespace App\Models;

use App\Traits\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes, Blameable;

    protected $primaryKey = 'usr_id';
    protected $guarded = ['usr_id'];

    protected $blameablePrefix = 'usr_';

    protected $dates = [
        'usr_created_at',
        'usr_updated_at',
        'usr_deleted_at',
    ];

    const CREATED_AT = 'usr_created_at';
    const UPDATED_AT = 'usr_updated_at';
    const DELETED_AT = 'usr_deleted_at';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi untuk transaksi
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'trx_user_id', 'usr_id');
    }

    // Blameable
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usr_created_by', 'usr_id');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usr_updated_by', 'usr_id');
    }

    public function deleted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usr_deleted_by', 'usr_id');
    }
}
