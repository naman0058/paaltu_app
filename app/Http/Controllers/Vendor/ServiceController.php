<?php

namespace App\Http\Controllers\Vendor;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\VendorService;
use App\Models\Feature;
use App\Models\Vendor;
use DataTables;
use Str;

class ServiceController extends Controller
{
    
    public function index()
    {
        $service=Service::get();
        return view('vendor.service.index',[
            'services'=>$service
        ]);

    }

    
    public function create()
    {
	   $picked_service_ids=VendorService::where('user_id',\Auth::user()->id)->get()->pluck('service_id')->toArray();
       $service=Service::whereNotIn('id',$picked_service_ids)->get();
       return view('vendor.service.create',[
        'services'=>$service
		]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
		'service_id' => 'required',
		'price' => 'required',
		'offer_price' => "less_than_price:{$request->price}",
		], [
		'offer_price.less_than_price' => "The offer price must be less than the {$request->price}.",
		]);
		
        $newData = new VendorService();
        $newData->service_id = $request->service_id;
        $newData->price = $request->price;
        $newData->offer_price = $request->offer_price;
        $newData->created_at=date('Y-m-d H:i:s');
        $newData->updated_at=date('Y-m-d H:i:s');
        $newData->created_by=\Auth::user()->id;
        $newData->updated_by=\Auth::user()->id;
        $newData->user_id = \Auth::user()->id;
        $newData->vendor_id = Vendor::getVendorId();
        $newData->save();
        \Session::flash('message','Created Successfully');
        return redirect('my-service');
    }  

    public function filter_service(Request $request)
    {
        if($request->ajax()){
          $data = VendorService::with('service')->where('user_id',auth()->user()->id);
          if($request->has('service_id') &&  $request->service_id != 'all')
          {
            $data=$data->whereHas('service',function($q)use($request){
				$q->where('service_id',$request->service_id);
			});
          }
         
          $data=$data->get();  
          return Datatables::of($data)

                ->addIndexColumn()
                ->addColumn('service_name', function($data) { return $data->service->service_name ?? ''; })
                ->addColumn('icon', function($data) { 
                    if($data->service->icon!='')
                    {
                        return '<img  src="'.asset('/public/serviceImage').'/'.$data->service->icon.'" style="width:30px;height:30px">';
                           
                    }else{
                        return '<img src="'. asset('/public/files/no image.jpg').'" style="width:30px;height:30px">';
                    }
                    })
                ->rawColumns(['service_name','icon','action'])
                ->addColumn('action',function($data){
                    $edit=url('my-service/'.$data->id.'/edit');
                    $show=route('my-service.show',$data->id);
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
        $service= VendorService::with('service')->find($request->id);
		if($service->user_id!=auth()->user()->id)
		{
			abort(403);
		}
        $view=view('vendor.service.view',[
            'services'=>$service
		]);
        echo $view;
    }


    public function edit($id)
    {
		$picked_service_ids=VendorService::where('id','!=',$id)
		->where('user_id',\Auth::user()->id)
		->get()
		->pluck('service_id')
		->toArray();
        $services=Service::whereNotIn('id',$picked_service_ids)->get();
        $service= VendorService::with('service')->find($id);
		if($service->user_id!=auth()->user()->id)
		{
			abort(403);
		}
		 
		//dd($services);
        return view('vendor.service.edit',[
            'service'=>$service,
            'services'=>$services
		]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
			'service_id' => 'required',
			'price' => 'required',
			'offer_price' => "less_than_price:{$request->price}",
		], [
			'offer_price.less_than_price' => "The offer price must be less than the {$request->price}.",
		]);

        $newData =VendorService::find($id);
        $newData->service_id = $request->service_id;
        $newData->price = $request->price;
        $newData->offer_price = $request->offer_price;
        $newData->updated_at=date('Y-m-d H:i:s');
        $newData->updated_by=\Auth::user()->id;
        $newData->user_id = \Auth::user()->id;
        $newData->vendor_id = Vendor::getVendorId();
        $newData->save();
        $newData->save();
        \Session::flash('message','Updated Successfully');
        return redirect('my-service');
        
    }
	public function destroy(Request $request)
    {
        $data=VendorService::find($request->id)->delete();
        echo 1; 
    }
    
}      