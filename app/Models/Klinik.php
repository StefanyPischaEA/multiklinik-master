<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klinik extends Model
{
    use HasFactory;
    protected $table = 'clinic';
    protected $guarded = [];
    public $timestamps = false;

    public function hospital(){
        return $this->belongsTo('\App\Models\Hospital', 'id_hospital');
    }
}
