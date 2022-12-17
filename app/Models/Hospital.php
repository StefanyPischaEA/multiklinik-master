<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;
    protected $table = 'hospital';

    public function klinik(){
        return $this->hasMany('\App\Models\Klinik', 'id_hospital', 'id');
    }
}
