<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    protected $table = 'doctor';
    protected $guarded = [];
    public $timestamps = false;

    public function clinic(){
        return $this->belongsTo('\App\Models\Klinik', 'id_clinic');
    }

    public function schedule(){
        return $this->hasMany('\App\Models\Schedule', 'id_doctor', 'id');
    }
}
