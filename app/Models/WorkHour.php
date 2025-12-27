<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'model_profile_id',
        'date',
        'hours',
    ];

    public function modelProfile()
    {
        return $this->belongsTo(ModelProfile::class);
    }
}
