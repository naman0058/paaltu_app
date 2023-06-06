<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breed;
use App\Models\PetCategory;
use DataTables;

class BreedController extends Controller
{
    public function index()
    {
        $breed=Breed::get();
        return view('admin.breed.index',[
            'breeds'=>$breed]);
    }
  
    public function create()
    {
       $breed=Breed::get();
       $pet=PetCategory::get();
       return view('admin.breed.create',[
        'breeds'=>$breed,
        'pets'=>$pet]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'breed_name'=>'required',
        ]);

        $newData = new Breed();
        $newData->breed_name = $request->breed_name;
        $newData->pet_category_id = $request->pet_category_id;
        $newData->created_by=\Auth::user()->id;
        $newData->updated_by=\Auth::user()->id;
        $newData->created_at=date('Y-m-d H:i:s');
        $newData->updated_at=date('Y-m-d H:i:s');
        $newData->save();
        \Session::flash('message','Created Successfully');
        return redirect('breed');
    }  

    public function filter_breed(Request $request)
    {
        $data = Breed::with('pet_category'); 
          if($request->has('breed_name') && !empty($request->breed_name))
        {
          $data=$data->where('breed_name','like','%'.$request->breed_name.'%');
        }
        $data=$data->get();  
          // echo '<pre>';
          // print_r($data->toArray());
          // exit();
           
        return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('breed_name', function($data) { return ucfirst($data->breed_name); })
                ->addColumn('category_name', function($data) { 
                if(isset($data->pet_category)) {
                    return ($data->pet_category->name); 
                  } 
                    return ''; 
                })
                ->rawColumns(['action'])

                ->addColumn('action',function($data){
                    $edit=url('breed/'.$data->id.'/edit');
                    return '<div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle breedViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>&nbsp
                            <button class="btn bg-danger btn-sm text-white rounded-circle deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button></div>';
                         
                })
                ->setRowId(function ($data) {
                    return "row_".$data->id;
               })
                ->make(true);
    }
     

    public function breedView(Request $request)
    {
        $breed= Breed::find($request->id);
        $view=view('admin.breed.view',[
            'breeds'=>$breed]);
        echo $view;
    }


    public function edit($id)
    {
        $breed=Breed::find($id);
        $pet=PetCategory::get();
        return view('admin.breed.edit',[
            'breeds'=>$breed,
            'pets'=>$pet]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'breed_name'=>'required',
        ]);
         
       $newData =Breed::find($id);
       $newData->breed_name = $request->breed_name;
       $newData->pet_category_id = $request->pet_category_id;
       $newData->updated_by = \Auth::user()->id;
       $newData->updated_at = date('Y-m-d H:i:s');
       $newData->save();
       \Session::flash('message','Updated Successfully');
       return redirect('breed');
        
    }

   
    public function destroy(Request $request)
    {
        $data=Breed::find($request->id)->delete();
        echo 1; 
    }

}