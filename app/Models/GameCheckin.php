<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameCheckin extends Model
{
    protected $table = 'GameCheckin';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    
    public $timestamps = true; 
    // Mapping nama kolom timestamp karena Prisma pake 'timestamp', bukan 'created_at'
    const CREATED_AT = 'timestamp'; 
    const UPDATED_AT = null; // Gak ada updated_at di schema

    protected $guarded = [];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    // Relasi ke Hotspot
    public function hotspot()
    {
        return $this->belongsTo(CulturalHostpot::class, 'hotspotId', 'id');
    }
}