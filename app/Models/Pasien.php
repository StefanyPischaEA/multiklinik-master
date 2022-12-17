<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'patient';
    protected $primaryKey = 'medical_record_id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('\App\Models\User', 'id_user');
    }

    public function hospital(){
        return $this->belongsTo('\App\Models\Hospital', 'id_hospital');
    }
}
