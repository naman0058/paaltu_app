<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Service;
class ServiceController extends BaseController
{
    public function services()
    {
        try{
            $data=Service::get();            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'services'=>$data
                ];
                return response()->json($response,200);
            }

        }catch(Excpection $e){

            return $this->sendError('', ['error'=>$e]); 
        }
    }


    public function serviceInfo($id)
    {
        try{
            $data=Service::find($id);            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'service-info'=>$data
                ];
                return response()->json($response,200);
            }else{
                return response()->json(['success'=>'false','message'=>'List is Empty'],404);
            }


        }catch(Excpection $e){

            return $this->sendError('', ['error'=>$e]); 
        }
    }

   



    
}
