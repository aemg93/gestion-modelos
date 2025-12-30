<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelProfile extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'nickname', 'email'];

    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'model_platform');
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function workHours()
    {
        return $this->hasMany(WorkHour::class);
    }
     public function user()
      { 
        return $this->belongsTo(User::class); 
    }
    

}
