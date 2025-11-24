<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CulturalHostpot extends Model
{
    protected $table = 'CulturalHotspot';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false; 
    protected $guarded = [];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    // Relasi: Hotspot ini ada di Provinsi mana?
    public function province()
    {
        return $this->belongsTo(Province::class, 'provinceId', 'id');
    }

    // Relasi: Siapa aja yang udah check-in di sini?
    public function checkIns()
    {
        return $this->hasMany(GameCheckin::class, 'hotspotId', 'id');
    }
}