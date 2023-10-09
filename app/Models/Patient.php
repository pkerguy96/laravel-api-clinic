<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
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
        'mutuelle',
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
