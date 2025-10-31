<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, SoftDeletes;

    // === Custom timestamp fields (menyesuaikan migration) ===
    const CREATED_AT = 'usr_created_at';
    const UPDATED_AT = 'usr_updated_at';
    const DELETED_AT = 'usr_deleted_at';

    /**
     * Kolom yang bisa diisi massal.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'usr_bio',
        'usr_activation',
        'usr_card_url',
        'usr_img_public_id',
        'usr_sys_note',
        'usr_created_by',
        'usr_updated_by',
        'usr_deleted_by',
    ];

    /**
     * Kolom yang disembunyikan ketika model di-serialize.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Casting kolom otomatis.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'usr_activation' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Getter inisial nama (misal “Nabil Halan" → “NH”)
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Relasi self-referencing (created/updated/deleted by)
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'usr_created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'usr_updated_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'usr_deleted_by');
    }
}
