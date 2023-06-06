<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Feature;
use DataTables;
use Str;

class ServiceController extends Controller
{
    
    public function index()
    {
        $service=Service::get();
        return view('admin.service.index',[
            'services'=>$service
        ]);

    }

    
    public function create()
    {
       $service=Service::get();
       return view('admin.service.create',[
        'services'=>$service]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'service_name'=>'required',
            'icon'=>'required',
            
        ]);

        $newData = new Service();
        $newData->service_name = $request->service_name;
        $newData->slug= Str::slug($request->service_name);
        $newData->created_by=\Auth::user()->id;
        $newData->updated_by=\Auth::user()->id;
        $newData->created_at=date('Y-m-d H:i:s');
        $newData->updated_at=date('Y-m-d H:i:s');
        $newData->icon = $request->icon;
        if($request->hasFile('icon'))
        {
          $file = ($request->file('icon'));
          $name = $file->getClientOriginalName();
          $path = public_path('/serviceImage'); 
          $file->move($path,$name);
          $newData->icon=$name;
        }
        $newData->save();
        \Session::flash('message','Created Successfully');
        return redirect('service');
    }  

    public function filter_service(Request $request)
    {
        if($request->ajax()){
          $data = Service::select('services.*');
          if($request->has('service_name') && !empty($request->service_name))
          {
            $data=$data->where('service_name','like','%'.$request->service_name.'%');
          }
         
          $data=$data->get();  
          return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('service_name', function($data) { return ucfirst($data->service_name); })
                ->addColumn('icon', function($data) { 
                    if($data->icon!='')
                    {
                        return '<img  src="'.asset('/public/serviceImage').'/'.$data->icon.'" style="width:30px;height:30px">';
                           
                    }else{
                        return '<img src="'. asset('/public/files/no image.jpg').'/'.$data->icon.'" style="width:30px;height:30px">';
                    }
                    })
                ->rawColumns(['service_name','icon','action'])
                ->addColumn('action',function($data){
                    $edit=url('service/'.$data->id.'/edit');
                    $show=route('service.show',$data->id);
                    return ' <div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle serviceViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye">
                    </i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>&nbsp
                            <button class="btn bg-danger btn-sm text-white rounded-circle deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp
                              </div>';              
                                //<a href="'.$show.'" value="'.$data->id.'" class="btn bg-info btn-sm text-white rounded-circle"><i class="fa fa-plus"></i></a></button>
                         
                })
                ->setRowId(function ($data) {
                     return "row_".$data->id;
               })
                ->make(true);    
        }
    } 

    public function serviceView(Request $request)
    {
        $service= Service::find($request->id);
        $view=view('admin.service.view',[
            'services'=>$service]);
        echo $view;
    }


    public function edit($id)
    {
        $service=Service::find($id);
        return view('admin.service.edit',[
            'services'=>$service]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'service_name'=>'required',
        ]);
         
        $newData =Service::find($id);
        if($newData->service_name != $request->service_name)
        {
         $newData->slug = Str::slug($request->service_name);
        }
        $newData ->service_name = $request->service_name;
        $newData->updated_by = \Auth::user()->id;
        $newData->updated_at = date('Y-m-d H:i:s');
        if($request->hasFile('icon'))
        {
          $file = ($request->file('icon'));
          $name = $file->getClientOriginalName();
          $path = public_path('/serviceImage'); 
          $file->move($path,$name);
          $newData->icon=$name;
        }
        $newData->save();
        \Session::flash('message','Updated Successfully');
        return redirect('service');
        
    }

    public function updateImages(Request $request)
    {
        $service=Service::find($request->update_image);
        $file=$request->file('edit_service_image');
        $name=$file->getClientOriginalName();
        $path = public_path('/serviceImage'); 
        $file->move($path,$name);
        $service->icon = $name;  
        echo asset('public/serviceImage/'.$name);
        $service->save();
    } 
    
    public function imagesDelete(Request $request)
    {
        $service=Service::find($request->id);
        $file =public_path()."/serviceImage/".$service->icon;  
        unlink($file);
        $service->icon='';
        $service->save();    
    }

    public function destroy(Request $request)
    {
        $data=Service::find($request->id)->delete();
        echo 1; 
    }

  
    public function featureStore(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
            'image'=>'required',
        ]);

        $newData = new Feature();
        $newData->title=$request->title;
        $newData->description=$request->description;
        $newData->service_id = $request->serviceFeature;
        $newData->image = $request->image;
        if($request->hasFile('image'))
        {
          $file = ($request->file('image'));
          $name = $file->getClientOriginalName();
          $path = public_path('/serviceImage'); 
          $file->move($path,$name);
          $newData->image=$name;
        }
        $newData->save();
        \Session::flash('message','Created Successfully');
        return view('admin.service.index');
    }

    public function show(Request $request,$id)
    {
        $service=Feature::select('features.*','services.id')
        ->leftjoin('services','services.id','features.service_id')
        ->where('features.service_id',$id)
        ->first();
        return view('admin.service.feature',[
            'services'=>$service,
            'service_id'=>$id
        ]);
    }
    
}      