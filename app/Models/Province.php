<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'Province';
    
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    
    public $timestamps = false;

    protected $guarded = [];

    // PENTING: Casting ini biar Laravel otomatis ubah JSON di DB jadi Array di PHP
    protected $casts = [
        'iconicInfoJson' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function hotspots()
    {
        return $this->hasMany(CulturalHostpot::class, 'provinceId', 'id');
    }
}