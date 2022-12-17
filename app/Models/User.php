<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';
    
    public function address(){
        return $this->belongsTo('\App\Models\Address', 'id_address');
    }
    
    public function contact(){
        return $this->belongsTo('\App\Models\Contact', 'id_contact');
    }
    
    public function identity(){
        return $this->belongsTo('\App\Models\Identity', 'id_identity');
    }

    public function loginInfo(){
        return $this->belongsTo('\App\Models\LoginInfo', 'id_login_info');
    }
}
