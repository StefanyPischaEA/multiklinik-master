<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    public function __construct(Pasien $model){
        $this->model = $model;
        $this->middleware('auth:api');
    }
    
    public function list(Request $request)
    {
        $limit = $request->limit ?? 10;
        $page = $request->page ?? 1;
        $pasien = $this->model->with(['user.address','user.contact','user.identity','user.loginInfo','hospital']);
        if($request->jenis == "baru" || $request->jenis=="lama"){
            $jenis = ($request->jenis=="baru")?0:1;
            $pasien = $pasien->where('verification_status_data',$jenis);
        }
        $pasien = $pasien->paginate($limit);
        return $this->paginateResponse($pasien);
    }
    public function detail($id){
        $data = $this->model->with(['user.address','user.contact','user.identity','user.loginInfo','hospital'])->findOrFail($id);
        return response()->json($data);
    }

    public function update(Request $request,$id){
        $this->validate($request,[
            'medical_record_id'=>'filled',
            'email' => 'email',
            'date_of_birth' => 'date|date_format:Y-m-d',
            'gender' => 'in:L,P',
        ]);

        $data = $this->model->findOrFail($id);
        \DB::transaction(function () use ($data,$request){
            $pasien = $request->only(['medical_record_id','verification_status_data']);
            $data->update($pasien);
            
            $address = [];
            if($request->alamat_ktp){
                $alamat = explode(',',$request->alamat_ktp);
                $address['identity_card_address_full_address'] = trim($alamat[0]) ?? '';
                $address['identity_card_addressrtrw'] = trim($alamat[1]) ?? '';
                $address['identity_card_address_village'] = trim($alamat[2]) ?? '';
                $address['identity_card_address_sub_district'] = trim($alamat[3]) ?? '';
                $address['identity_card_address_district'] = trim($alamat[4]) ?? '';
                $address['identity_card_address_province'] = trim($alamat[5]) ?? '';
            }
            if($request->alamat_domisili){
                $alamat = explode(',',$request->alamat_domisili);
                $address['residence_address_full_address'] = trim($alamat[0]) ?? '';
                $address['residence_addressrtrw'] = trim($alamat[1]) ?? '';
                $address['residence_address_village'] = trim($alamat[2]) ?? '';
                $address['residence_address_sub_district'] = trim($alamat[3]) ?? '';
                $address['residence_address_district'] = trim($alamat[4]) ?? '';
                $address['residence_address_province'] = trim($alamat[5]) ?? '';
            }
            $data->user->address->update($address);

            $contact = $request->only(['email','phone_number']);
            $data->user->contact->update($contact);

            $identity = $request->only(['first_name','last_name','date_of_birth','gender','identity_card_number_ktp','identity_card_number_bpjs']);
            if($request->docs_ktp){
                $identity['docs_ktp'] = $request->docs_ktp;
                if($request->hasFile('docs_ktp')){
                    $docs = $request->file('docs_ktp')->move('upload/ktp',$data->medical_record_id.'.'.$request->file('docs_ktp')->getClientOriginalExtension());
                    $identity['docs_ktp'] = $docs;
                }
            }
            if($request->docs_bpjs){
                $identity['docs_bpjs'] = $request->docs_bpjs;
                if($request->hasFile('docs_bpjs')){
                    $docs = $request->file('docs_bpjs')->move('upload/bpjs',$data->medical_record_id.'.'.$request->file('docs_bpjs')->getClientOriginalExtension());
                    $identity['docs_bpjs'] = $docs;
                }
            }
            $data->user->identity->update($identity);
        });
        return response()->json(['message'=>'successfully updated']);
    }
}