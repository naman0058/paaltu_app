<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Breed;
use App\Models\PetCategory;
use App\Models\PetProfile;
use App\Models\PetGallery;
class PetProfileController extends BaseController
{


    public function profile($id)
    {
        try{
            $data=PetProfile::with('category','breed','petItems')->find($id);            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'pet profile-info'=>$data
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
