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
        'note',
    ];
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
    public function Ordonance()
    {
        return $this->hasMany(Ordonance::class, 'patient_id');
    }
}
