<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\VendorService;
use App\Models\Service;
use App\Models\User;
use DataTables;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.vendor.index');
    }
    public function create()
    {
        return view('admin.vendor.create');
    }
	public function show($id)
    {
        $data=Vendor::find($id);
        return view('admin.vendor.view',[
			'data'=>$data
		]);
    }
	public function store(Request $request)
    {
        $request->validate([
			'name'=>'required',
			'email'=>'required|unique:users',
			'mobile'=>'required|unique:users',
		]);
		$password=substr(str_shuffle('0123456789ABCDEFGHI'),0,6);
		$user=new User();
		$user->name=$request->name;
		$user->email=$request->email;
		$user->mobile=$request->mobile;
		$user->password=bcrypt($password);
		$user->user_type='vendor';
		$user->save();
		$data=new Vendor();
		$data->name=$request->name;
		$data->email=$request->email;
		$data->user_id=$user->id;
		$data->mobile=$request->mobile;
		$data->alt_mobile=$request->alt_mobile;
		$data->address=$request->address;
		if($request->hasFile('icon'))
		{
			$file=$request->file('icon');
			$name=time().str_shuffle(time()).'.'.$file->extension();
			$path=public_path().'/uploads/vendor/';	
			$file->move($path,$name);
			$data->icon=$name;
			$user->dp=$name;
			$user->save();	
		}
		if($data->save())
		{
			$to = $request->email;
			$subject = "PAALTU - Registration Suuccessful";
			$headers = "From: no-reply@paaltu.com";
			$message="Your Paaltu Login details \nEmail : ".$request->email."  \nPassword :  ".$password;
			mail($to, $subject, $message, $headers);
			\Session::flash('message','Created Successfully');
			return redirect('vendors');
		}else{
			$user->delete();
			\Session::flash('message','Something Went Wrong');
			return back();
		}
		//
		
    }
	public function edit($id)
    {
		$data=Vendor::find($id);
        return view('admin.vendor.update',[
			'data'=>$data
		]);
    }
	public function update(Request $request,$id)
    {
		$data=Vendor::find($id);
        $request->validate([
			'name'=>'required',
			'email'=>'required|unique:users,email,'.$data->user_id,
			'mobile'=>'required|unique:users,mobile,'.$data->user_id,
		]);
		 
		
		
		$data->name=$request->name;
		$data->email=$request->email;
		$data->mobile=$request->mobile;
		$data->alt_mobile=$request->alt_mobile;
		$data->address=$request->address;
		if($request->hasFile('icon'))
		{
			$file=$request->file('icon');
			$name=time().str_shuffle(time()).'.'.$file->extension();
			$path=public_path().'/uploads/vendor/';	
			$file->move($path,$name);
			$data->icon=$name;
			 
		}
		if($data->save())
		{
			$user=User::find($data->user_id);
			$user->name=$request->name;
			$user->email=$request->email;
			$user->mobile=$request->mobile;
			$user->dp=$data->icon;
			$user->save();

			\Session::flash('message','Updated Successfully');
			return redirect('vendors');
		}else{
			\Session::flash('message','Something Went Wrong');
			return back();
		}
		//
		
    }
    public function filter(Request $request)
    {
        $data = Vendor::with(['user']);
        
        $data=$data->get();  

        return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('name', function($data) { return $data->name; })
                ->editColumn('icon', function($data) { 
					$path= $data->icon != '' ? asset('/public/uploads/vendor/').'/'.$data->icon : 'https://ui-avatars.com/api/?name='.$data->name.'+Doe&background=random'; 
					return "<img src='".$path."' height='40' width='40'>";
				})
                ->rawColumns(['action','icon'])
                ->addColumn('action',function($data){
                    $html= '<a href="'.url('vendors/'.$data->id).'" class="btn btn-info"><i class="fa fa-eye"></i></a>&nbsp;';   
                    $html.= '<a href="'.url('vendors/'.$data->id.'/edit').'" class="btn btn-primary"><i class="fa fa-edit"></i></a>&nbsp;';   
                    $html.= '<button class="btn btn-danger deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp;'; 
                	$html.= '<a href="'.url('vendors-services/'.$data->id).'" class="btn btn-info" style="color:white;">View Services</a>&nbsp;';
					return $html;
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);
    }
	public function destroy($id)
	{
		$vendor=Vendor::find($id);
		if(isset($vendor))
		{
			$user=User::where('id',$vendor->user_id)->where('user_type','vendor')->delete();
			$vendor->delete();
			echo 1;
		}
	}

	public function vendorsServices()
	{ 
		return view('admin.vendor.vendor-services');
	} 

	public function vendorsServicesFilter(Request $request)
    {
        $data = VendorService::where('vendor_id', $request->id)->whereNull('deleted_at')->get(); 

        return Datatables::of($data)

                ->addIndexColumn()
                //->addColumn('name', function($data) { return $data->name; })
                ->addColumn('service', function($data) {
                	$service_name = Service::where('id', $data->service_id)->pluck('service_name')->first(); 
                	return $service_name; 
                })
                ->editColumn('icon', function($data) { 
					$path= $data->icon != '' ? asset('/public/uploads/vendor/').'/'.$data->icon : 'https://ui-avatars.com/api/?name='.$data->name.'+Doe&background=random'; 
					return "<img src='".$path."' height='40' width='40'>";
				})
                ->rawColumns(['action','icon'])
                ->addColumn('action',function($data){
                    $html= '<a href="'.url('vendors/'.$data->id).'" class="btn btn-info"><i class="fa fa-eye"></i></a>&nbsp;';   
                    $html.= '<a href="'.url('vendors/'.$data->id.'/edit').'" class="btn btn-primary"><i class="fa fa-edit"></i></a>&nbsp;';   
                    $html.= '<button class="btn btn-danger deleteBtn" type="button" value="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp;'; 
                    $html.= '<a href="'.url('vendors-services/'.$data->id).'" class="btn btn-info" style="color:white;">View Services</a>&nbsp;';
					return $html;
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);
    }
     
    
}


   