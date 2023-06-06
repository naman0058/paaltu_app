<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\Blog;
class BlogController extends BaseController
{
    public function blog()
    {
        try{
            $data=Blog::get();
        
            if($data)
            {	
            	foreach($data as $datas){
                	$datas->image = url('/public/blogImage').'/'.$datas->image;
                }
                $response=[
                    'success' =>true,
                    'blog'=>$data
                ];
                return response()->json($response,200);
            }

        }catch(Excpection $e){

            return $this->sendError('', ['error'=>$e]); 
        }
    }


    public function blogInfo($id)
    {
        try{
            $data=Blog::find($id);            
            if($data)
            {
                $response=[
                    'success' =>true,
                    'blog-info'=>$data
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
