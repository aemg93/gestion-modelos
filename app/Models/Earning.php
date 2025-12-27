<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    protected $table = 'earnings';

    protected $fillable = [
        'model_profile_id',
        'amount',
        'date',
    ];

    // Relación con ModelProfile
    public function modelProfile()
    {
        return $this->belongsTo(ModelProfile::class);
    }
}
