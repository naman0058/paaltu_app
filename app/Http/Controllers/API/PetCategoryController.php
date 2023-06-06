<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\PetCategory;
class PetCategoryController extends BaseController
{
    public function petCategory()
    {
        try{
            $data=PetCategory::get();            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'pet-category'=>$data
                ];
                return response()->json($response,200);
            }

        }catch(Excpection $e){

            return $this->sendError('', ['error'=>$e]); 
        }
    }


    public function petCategoryInfo($id)
    {
        try{
            $data=PetCategory::find($id);            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'petcategory-info'=>$data
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
