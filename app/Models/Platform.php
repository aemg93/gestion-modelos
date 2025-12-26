<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    // Relaciones futuras (ejemplo con ModelProfile)
    public function models()
    {
        return $this->belongsToMany(ModelProfile::class, 'model_platform');
    }
}
