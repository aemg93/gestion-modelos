<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nickname', 'email'];

    // 👇 Relación muchos a muchos con Platform
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'model_platform');
    }

    // 👇 Relación uno a muchos con Earnings
    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    // 👇 Relación uno a muchos con WorkHours
    public function workHours()
    {
        return $this->hasMany(WorkHour::class);
    }
}
