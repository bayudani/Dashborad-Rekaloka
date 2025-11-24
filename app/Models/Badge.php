<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'Badge';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    
    public $timestamps = true;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}