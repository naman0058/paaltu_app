<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PetCategory;
use DataTables;

class PetcategoryController extends Controller
{
	public function index()
    {
        $data=PetCategory::get();
        return view('admin.petcategory.index',[
            'datas'=>$data]);

    }

    
    public function create()
    {
       $data=PetCategory::get();
       return view('admin.petcategory.create',[
        'datas'=>$data]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'icon'=>'required',
            'description'=>'required',
        ]);

        $newData = new PetCategory();
        $newData->name = $request->name;
        $newData->description = $request->description;
        $newData->created_by=\Auth::user()->id;
        $newData->updated_by=\Auth::user()->id;
        $newData->created_at=date('Y-m-d H:i:s');
        $newData->updated_at=date('Y-m-d H:i:s');
        $newData->icon = $request->icon;
        if($request->hasFile('icon'))
        {
          $file = ($request->file('icon'));
          $name = $file->getClientOriginalName();
          $path = public_path('/petcategoryImage'); 
          $file->move($path,$name);
          $newData->icon=$name;
        }
        $newData->save();
        \Session::flash('message','Created Successfully');
        return redirect('pet-category');
    }  

    public function filter_pet_category(Request $request)
    {
        if($request->ajax()){
          $data = PetCategory::select('pet_category.*');
          if($request->has('name') && !empty($request->name))
          {
            $data=$data->where('name','like','%'.$request->name.'%');
          }
         
          $data=$data->get();  
          return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('name', function($data) { return ucfirst($data->name); })
                ->addColumn('icon', function($data) { 
                    if($data->icon!='')
                    {
                        return '<img  src="'.asset('/public/petcategoryImage').'/'.$data->icon.'" style="width:30px;height:30px">';          
                    }
                    else
                    {
                        return '<img src="'. asset('/public/files/no image.jpg').'/'.$data->icon.'" style="width:30px;height:30px">';
                    }
                    })

                ->rawColumns(['name','icon','action'])
                ->addColumn('action',function($data){
                    $edit=url('pet-category/'.$data->id.'/edit');
                    return '<div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle pet_categoryViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>&nbsp
                            <button class="btn bg-danger btn-sm text-white rounded-circle deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button></div>';
                         
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);     
            }
    }  

    public function petcategoryView(Request $request)
    {
        $data= PetCategory::find($request->id);
        $view=view('admin.petcategory.view',[
            'datas'=>$data]);
        echo $view;
    }


    public function edit($id)
    {
        $data=PetCategory::find($id);
        return view('admin.petcategory.edit',[
            'datas'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            'description'=>'required',
        ]);
         
       $newData =PetCategory::find($id);
       $newData ->name = $request->name;
       $newData ->description = $request->description;
	   $newData->updated_by = \Auth::user()->id;
       $newData->updated_at = date('Y-m-d H:i:s');
       if($request->hasFile('icon'))
        {
          $file = ($request->file('icon'));
          $name = $file->getClientOriginalName();
          $path = public_path('/petcategoryImage'); 
          $file->move($path,$name);
          $newData->icon=$name;
        }
        $newData->save();
        \Session::flash('message','Updated Successfully');
        return redirect('pet-category');
        
    }

    public function updateImages(Request $request)
    {
        $data=PetCategory::find($request->update_image);
        $file=$request->file('edit_pet_image');
        $name=$file->getClientOriginalName();
        $path = public_path('/petcategoryImage'); 
        $file->move($path,$name);
        $data->icon = $name;  
        echo asset('public/petcategoryImage/'.$name);
        $data->save();
    } 
    
    public function imagesDelete(Request $request)
    {
        $data=PetCategory::find($request->id);
        $file =public_path()."/petcategoryImage/".$data->icon;  
        unlink($file);
        $data->icon='';
        $data->save(); 
    
    }

    public function destroy(Request $request)
    {
        $data=PetCategory::find($request->id)->delete();
        echo 1; 
    }

    
}