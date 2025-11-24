<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName; // Tambahin ini biar lebih afdol
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Implement HasName biar Filament tau kita punya custom name
class User extends Authenticatable implements FilamentUser, HasName
{
    use Notifiable;

    protected $table = 'User'; 

    protected $fillable = [
        'email',
        'username',
        'role'
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

    const CREATED_AT = 'createdAt';

    protected $guarded = []; 

    // --- SESSION & TOKEN FIX ---
    public function setRememberToken($value)
    {
        // Do nothing
    }

    public function getRememberToken()
    {
        return null; 
    }

    public function getRememberTokenName()
    {
        return null; 
    }
    // ---------------------------

    // --- FIX YANG BENER DISINI ---
    // Ganti dari getUserName jadi getFilamentName
    public function getFilamentName(): string
    {
        // Pastikan return string, kalau username null, balikin string kosong atau email
        return $this->username ?? $this->email ?? 'Admin';
    }
    // -----------------------------

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin' || $this->role === 'user'; 
    }

    public function checkIns()
    {
        return $this->hasMany(GameCheckin::class, 'userId');
    }

    public function badges()
    {
        return $this->hasMany(Badge::class, 'userId');
    }
}