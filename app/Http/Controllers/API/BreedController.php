<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\PetCategory;
use App\Models\Breed;
class BreedController extends BaseController
{
    public function breed()
    {
        try{
            $data=Breed::with('pet_category')->get();            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'breed'=>$data
                ];
                return response()->json($response,200);
            }

        }catch(Excpection $e){

            return $this->sendError('', ['error'=>$e]); 
        }
    }


    public function breedInfo($id)
    {
        try{
            $data=Breed::with('pet_category')->find($id);            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'breed-info'=>$data
                ];
                return response()->json($response,200);
            }else{
                return response()->json(['success'=>'false','message'=>'List is Empty'],404);
            }


        }catch(Excpection $e){

            return $this->sendError('', ['error'=>$e]); 
        }
    }
    
    //breed list from pet category
    public function categoryBreed($id)
    {
        try{
            $data=Breed::with('pet_category')->where('pet_category_id',$id)->get();            
            if(count($data)>0)
            {
                $response=[
                    'success' =>true,
                    'breed'=>$data
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
