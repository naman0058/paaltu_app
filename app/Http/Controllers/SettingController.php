<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use DataTables;

class SettingController extends Controller
{
    public function index()
    {
     return view('admin.setting.index');
    }

    public function create()
    {
        $setting=Setting::get();
        return view('admin.setting.create',[
            'settings'=>$setting]);    
    }

    public function store(Request $request)
    {
        $newData= new Setting();
        $newData->label=$request->label;
        $newData->value=$request->value;
        $newData->save();
        return redirect('setting');
    }

    public function edit($id)
    {
        $setting=Setting::find($id);
        return view('admin.setting.edit',[
            'settings'=>$setting]);
    }

    public function update(Request $request, $id)
    {
        $newData=Setting::find($id);
        $newData->label=$request->label;
        $newData->value=$request->value;
        $newData->save();
        \Session::flash('message','Updated Successfully');
        return redirect('setting');
    }


    public function filter_setting(Request $request)
    {
        if($request->ajax()){
          $data = Setting::select('setting.*');
          if($request->has('label') && !empty($request->label))
          {
            $data=$data->where('label','like','%'.$request->label.'%');
          }
           if($request->has('value') && !empty($request->value))
          {
            $data=$data->where('value','like','%'.$request->value.'%');
          }
         
         
          $data=$data->get();  
          return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('value', function($data) { return ucfirst($data->value); })
                ->addColumn('label', function($data) { return ucfirst($data->label); })
                ->rawColumns(['label','value','action'])
                ->addColumn('action',function($data){
                    $edit=url('setting/'.$data->id.'/edit');
                    return '<div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle settingViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>';
                         
                })
                ->setRowId(function ($data) {
                     return "row_".$data->id;
               })
                ->make(true);

            
        }
    }

    public function settingView(Request $request)
    {
        $setting= Setting::find($request->id);
        $view=view('admin.setting.view',[
            'settings'=>$setting]);
        echo $view;
    }

    public function destroy(Request $request)
    {
        $data=Setting::find($request->id)->delete();
        echo 1; 
    }
    
}
