<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dokter;
use App\Models\Schedule;
use App\Models\AppointmentQueue;

class MasterController extends Controller
{
    public function __construct(Dokter $dokter,AppointmentQueue $aq, Schedule $schedule){
    	$this->dokter = $dokter;
        $this->schedule = $schedule;
        $this->aq = $aq;
        $this->middleware('auth:api');
    }

    public function clinic(){
    	$data = auth()->user()->hospital->klinik;
    	return response()->json($data);
    }

    public function dokter(Request $request){
        $this->validate($request,[
            'id_clinic'=>'required'
        ]);
    	$data = $this->dokter->whereHas('clinic',function($q){
                $q->where('id_hospital',auth()->user()->id_hospital);
            })  
            ->where('id_clinic',$request->id_clinic)
            ->get();
    	return response()->json($data);
    }

    public function tanggal(Request $request){
        $this->validate($request,[
            'id_doctor'=>'required'
        ]);
        $data = $this->aq->whereDate('date_appointment','>=',\Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d'))
                    ->whereHas('schedule',function($q) use ($request){
                        $q->where('id_doctor',$request->id_doctor);
                    })
                    ->orderBy('date_appointment','asc')
                    ->groupBy('date_appointment')
                    ->pluck('date_appointment');
        return response()->json($data);
    }

    public function waktu(Request $request){
        $this->validate($request,[
            'id_doctor'=>'required',
            'tanggal'=>'required'
        ]);
        $data = $this->aq->with('schedule')->select('id as id_appointment_queue','id_schedule','date_appointment')
                    ->whereDate('date_appointment',$request->tanggal)
                    ->whereHas('schedule',function($q) use ($request){
                        $q->where('id_doctor',$request->id_doctor);
                    })->get();
        return response()->json($data);
    }
}