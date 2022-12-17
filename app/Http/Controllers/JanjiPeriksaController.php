<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class JanjiPeriksaController extends Controller
{
    public function __construct(Appointment $model){
        $this->model = $model;
        $this->middleware('auth:api');
    }
    
    public function list(Request $request)
    {
        $limit = $request->limit ?? 10;
        $page = $request->page ?? 1;
        $this->validate($request,[
            'id_clinic'=>'required',
            'id_doctor'=>'required',
            'id_schedule'=>'required',
            'id_appointment_queue'=>'required',
        ]);

        $data = $this->model->with('pasien.user.identity')
            ->where('id_clinic',$request->id_clinic)
            ->where('id_doctor',$request->id_doctor)
            ->where('id_schedule',$request->id_schedule)
            ->where('id_appointment_queue',$request->id_appointment_queue);

        if($request->status){
            $data = $data->where('status',$request->status);
        }
        $data = $data->paginate($limit);

        $tmp = $data->getCollection()->map(function ($row, $key) {
            $item = collect([
                'id'=>$row->id,
                'status'=>$row->status,
                'queue_number'=>$row->queue_number,
                'pasien'=>[
                    'first_name'=> $row->pasien->user->identity->first_name,
                    'last_name'=> $row->pasien->user->identity->last_name,
                    'login_info'=> $row->pasien->user->loginInfo,
                ],
            ]);
            return $item;
        });
        $data->setCollection($tmp);
        return $this->paginateResponse($data);
    }

    public function detail($id){
        $data = $this->model->with(['klinik','appointmentQueue','doctor','pasien.user.identity','pasien.user.loginInfo','schedule'])->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request,$id){
        $input = $request->only(['status']);
        switch ($request->status) {
            case 'CHECKED_OUT':
                $input['checked_out_date_time'] = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d-H-i-s');
                break;
            case 'CHECKED_IN':
                $input['checked_in_date_time'] = \Carbon\Carbon::now('Asia/Jakarta')->format('Y-m-d-H-i-s');
                break;
            default:
                break;
        }
        $data = $this->model->findOrFail($id)->update($input);
        return response()->json(['message'=>'successfully updated']);
    }
    public function praktikSelesai(Request $request){
        $data = $this->model->where('status','WAITING')
                    ->where('id_clinic',$request->id_clinic)
                    ->where('id_doctor',$request->id_doctor)
                    ->where('id_schedule',$request->id_schedule)
                    ->where('id_appointment_queue',$request->id_appointment_queue)
                    ->update(['status'=>'CHECKED_OUT']);
        return response()->json(['message'=>'successfully updated']);
    }
}