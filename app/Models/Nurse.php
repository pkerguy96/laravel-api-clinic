<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    use HasFactory;
    protected $fillable = [
        'nom',
        'prenom',
        'cin',
        'date',
        'address',
        'sex',
        'phone_number',
        'doctor_id',
    ];
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
