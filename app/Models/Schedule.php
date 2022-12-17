<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule';
    protected $guarded = [];
    public $timestamps = false;

    public function doctor(){
        return $this->belongsTo('\App\Models\Dokter', 'id_doctor');
    }

    public function appointmentQueue(){
        return $this->hasMany('\App\Models\AppointmentQueue', 'id_schedule', 'id');
    }
}
