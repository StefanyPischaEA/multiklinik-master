<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointment';
    protected $guarded = [];
    public $timestamps = false;

    public function klinik(){
        return $this->belongsTo('\App\Models\Klinik', 'id_clinic');
    }
    
    public function appointmentQueue(){
        return $this->belongsTo('\App\Models\AppointmentQueue', 'id_appointment_queue');
    }
    
    public function doctor(){
        return $this->belongsTo('\App\Models\Dokter', 'id_doctor');
    }

    public function pasien(){
        return $this->belongsTo('\App\Models\Pasien', 'medical_record_id','medical_record_id');
    }

    public function schedule(){
        return $this->belongsTo('\App\Models\Schedule', 'id_schedule');
    }

    public function hospital(){
        return $this->belongsTo('\App\Models\Hospital', 'id_hospital');
    }
}
