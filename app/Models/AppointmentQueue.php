<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentQueue extends Model
{
    use HasFactory;
    protected $table = 'appointment_queue';
    protected $guarded = [];
    public $timestamps = false;

    public function schedule(){
        return $this->belongsTo('\App\Models\Schedule', 'id_schedule');
    }
}
