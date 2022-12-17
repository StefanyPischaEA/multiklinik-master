<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function paginateResponse($data,$code=200){
    	$response = [
    		'count'=>$data->count(),
    		'total'=>$data->total(),
    		'per_page'=>(int)$data->perPage(),
    		'current_page'=>$data->currentPage(),
    		'total_page'=>$data->lastPage(),
    		'items'=>$data->items()
    	];
        return response()->json($response);	
    }
}
