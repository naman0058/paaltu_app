<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Interest;
use App\Models\PetProfile;
use DataTables;

class InterestController extends Controller
{
	public function index()
	{
		$interest=Interest::get();
		return view('admin.interest.index',[
			'interests'=>$interest]);
		
	}

	public function create()
	{
		$interest=Interest::get();
		$pet=PetProfile::get();
		return view('admin.interest.create',[
			'interests'=>$interest,
			'pets'=>$pet]);
			
	}

	public function filter_interest(Request $request)
	{
		if($request->ajax()){
        $data = Interest::select('interest_table.*');
        if($request->has('date_and_time') && !empty($request->date_and_time))
        {
          $data=$data->where('date_and_time','like','%'.$request->date_and_time.'%');
        }
         
        $data=$data->get();  
          return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('date_and_time', function($data) { return ucfirst($data->date_and_time); })

                ->rawColumns(['name','action'])
                ->addColumn('action',function($data){
                    $edit=url('interest/'.$data->id.'/edit');
                    return '<div class="d-flex"><button class="btn bg-primary btn-sm text-white rounded-circle interestViewBtn sharp mr-1" type="button" value="'.$data->id.'" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-eye"></i></button>&nbsp
                            <a href="'.$edit.'" class="btn bg-secondary btn-sm text-white rounded-circle"><i class="fa fa-pencil"></i></a>&nbsp
                            <button class="btn bg-danger btn-sm text-white rounded-circle deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button></div>';
                         
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);     
            }
    }
    public function store(Request $request)
    {
    	$request->validate([
    		'date_and_time'=>'required',
    		'latitude'=>'required',
    		'longitude'=>'required',
    	]);

    	$newData = new Interest();
    	$newData->date_and_time=date('Y-m-d H:i:s');
    	$newData->latitude=$request->latitude;
    	$newData->created_by=\Auth::user()->id;
        $newData->updated_by=\Auth::user()->id;
        $newData->created_at=date('Y-m-d H:i:s');
        $newData->updated_at=date('Y-m-d H:i:s');
    	$newData->longitude=$request->longitude;
    	$newData->from_pet_id=$request->from_pet_id;
    	$newData->to_pet_id=$request->to_pet_id;
    	$newData->save();
    	\Session::flash('message','Created Successfully');
    	return redirect('interest');
    }  

    public function interestView(Request $request)
    {
    	$data = Interest::select('interest_table.*','pet_name')
            ->leftJoin('pet_profile', 'pet_profile.id', 'interest_table.to_pet_id')
            //->where('pet_profile.id', $id)
            ->first();
        return view('admin.interest.view', [
            'datas' => $data]);
    }

    public function destroy(Request $request)
    {
        $data=Interest::find($request->id)->delete();
        echo 1; 
    }

    public function edit($id)
    {
    	$interest=Interest::find($id);
    	$pet=PetProfile::get();
    	return view('admin.interest.edit',[
    		'interest'=>$interest,
    		'pets'=>$pet
    	]);
    }

    public function update(Request $request,$id)
    {
    	$request->validate([
    		'date_and_time'=>'required',
    		'longitude'=>'required',
    		'latitude'=>'required',
    	]);

    	$newData=Interest::find($id);
    	$newData->date_and_time=date('Y-m-d H:i:s');
    	$newData->latitude=$request->latitude;
    	$newData->longitude=$request->longitude;
    	$newData->from_pet_id=$request->pet_id;
    	$newData->updated_by=\Auth::user()->id;
    	$newData->updated_at=date('Y-m-d H:i:s');
    	$newData->from_pet_id=$request->from_pet_id;
    	$newData->to_pet_id=$request->to_pet_id;
    	$newData->save();
    	\Session::flash('message','Updated Successfully');
    	return redirect('interest');
    }

	
}