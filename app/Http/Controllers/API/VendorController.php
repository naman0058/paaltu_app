<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Service;
use App\Models\User;
use App\Models\Vendor; 
use App\Models\VendorSetting;
use App\Models\Booking;
use App\Models\VendorService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class VendorController extends BaseController
{

    public function getUser()
    {
        $is_vendor_added = Vendor::where('user_id', auth()->user()->id)->count();
        $is_vendor_added = $is_vendor_added == 0 ? false : true;
        return response()->json([
            'result' => true,
            'data' => auth()->user()->makeHidden('dp_url'),
            'is_vendor_added' => $is_vendor_added,
        ]);
    }
    public function editProfile(Request $request)
    {	
    	 
        $validator = \Validator::make($request->all(), [
            'email' => 'string|email|max:255|unique:users,email,' . auth()->user()->id, 
            'name' => 'required',
            'mobile' => 'unique:users,mobile,' . auth()->user()->id, 
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $errors = implode(" ", $errors->all());
            return response()->json([
                'message' => $errors,
            ], 422);
        }

        $thisData = [];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . str_shuffle(time()) . '.' . $file->extension();
            $path = public_path() . '/uploads/vendor/';
            $file->move($path, $name);
            $thisData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'dp' => $name,
                'mobile' => $request->mobile,
            	'alt_mobile' => $request->alt_mobile,
            	'address' => $request->address,
            ];
            $vendor = Vendor::where('user_id', auth()->user()->id)->first();
            if (!isset($vendor)) {
                $vendor = new Vendor();
            }
            $vendor->user_id = auth()->user()->id;
            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->mobile = $request->mobile;
            $vendor->alt_mobile = $request->alt_mobile;
            $vendor->address = $request->address;
            $vendor->icon = $name;
            $vendor->save();
        } 
    	else {
            $thisData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'mobile' => $request->mobile,
            	'alt_mobile' => $request->alt_mobile,
            	'address' => $request->address,
            ];
            $vendor = Vendor::where('user_id', auth()->user()->id)->first();
            if (!isset($vendor)) {
                $vendor = new Vendor();
            }
            $vendor->user_id = auth()->user()->id;
            $vendor->name = $request->name;
            $vendor->email = $request->email;
            $vendor->mobile = $request->mobile;
            $vendor->alt_mobile = $request->alt_mobile;
            $vendor->address = $request->address;
            $vendor->save();
			//setting for vendor
			$settingsArray=['OPENING_TIME'=>'09:00','CLOSING_TIME'=>'17:00'];
			foreach($settingsArray as $key=>$item)
			{
				if(VendorSetting::where(['user_id'=>auth()->user()->id,'label'=>$key])->count()==0)
				{
					$newVendorSetting=new VendorSetting();
					$newVendorSetting->user_id=auth()->user()->id;
					$newVendorSetting->vendor_id=$vendor->id;
					$newVendorSetting->label=$key;
					$newVendorSetting->value=$item;
					$newVendorSetting->save();
				}
			}
        }
        $data = User::find(auth()->user()->id)
            ->update($thisData);

        $userData = User::find(auth()->user()->id)->makeHidden('dp_url');
        $userData->address = $vendor->address;
    	$userData->alt_mobile = $vendor->alt_mobile;
        return response()->json([
            'result' => true,
            'data' => $userData,
        ]);
    }
    public function getMainServices()
    {
        $data = Service::get()
            ->makeHidden(['created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at']);
        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }
    public function getServices(Request $request)
    {

        $data = VendorService::with(['service' => function ($q) {
            $q->select('id', 'service_name', 'icon', 'description');
        }])->where('user_id', auth()->user()->id);
        $data = $data->get()->makeHidden(['created_by', 'updated_by', 'created_at', 'updated_at', 'deleted_at']);
    	foreach($data as $datas){
        	if($datas->service_image){
        		$datas->service_image = url('/public/uploads/vendor_service/').'/'.$datas->service_image; 
            }else{
            	$datas->service_image = NULL;
            }
        }
        return response()->json([
            'result' => true,
            'data' => $data,
        ]);
    }
    public function getService($id)
    {
        $data = VendorService::with(['service' => function ($q) {
            $q->select('id', 'service_name', 'icon', 'description');
        }])
        ->find($id);
        if (isset($data)) {  
            		if($data->service_image){
                		$data->service_image = url('/public/uploads/vendor_service/').'/'.$data->service_image; 
            		}else{
                		$data->service_image = NULL;
            		} 
            $data = $data->makeHidden([
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
			return response()->json([
           	 	'result' => true,
            	'data' => $data,
        	]);
        } 
		return response()->json([
			'result' => false,
			'message' => 'Record not found',
		]); 
    }
    public function storeService(Request $request)
    {
        $vendor_id = Vendor::getVendorId();
        $validateParams = ['vendor_id' => $vendor_id, 'service_id' => $request->service_id];
        $validator = Validator::make($request->all(), [
            'service_id' => [
                'required',
                Rule::unique('vendor_services')->where(function ($query) use ($validateParams) {
                    return $query->where('vendor_id', $validateParams['vendor_id'])->where('service_id', $validateParams['service_id']);
                }),
            ],
            'price' => 'required',
            'offer_price' => "less_than_price:price",
			'slot_hour' => "required",
            'slot_minutes' => "required",
        ], [
            'service_id.unique' => 'The service ID is already taken  .',
            'offer_price.less_than_price' => "The offer price must be less than the {$request->price}.",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
		if ($request->hasFile('service_image')) {
            $file = $request->file('service_image');
            $name = time() . str_shuffle(time()) . '.' . $file->extension();
            $path = public_path() . '/uploads/vendor_service/';
            $file->move($path, $name);
        }else{
        	$name = NULL;
        }
        $newData = new VendorService();
        $newData->service_id = $request->service_id;
        $newData->price = $request->price;
        $newData->offer_price = $request->offer_price;
        $newData->slot_hour = $request->slot_hour;
        $newData->slot_minutes = $request->slot_minutes;
        $newData->created_at = date('Y-m-d H:i:s');
        $newData->updated_at = date('Y-m-d H:i:s');
        $newData->created_by = \Auth::user()->id;
        $newData->updated_by = \Auth::user()->id;
        $newData->user_id = \Auth::user()->id;
        $newData->vendor_id = Vendor::getVendorId();
    	$newData->service_image = $name;
        $newData->save();
        return response()->json([
            'result' => true,
            'message' => "Created Successfully",
        ]);
    }
    public function updateService(Request $request)
    {
        $vendor_id = Vendor::getVendorId();
        $validateParams = ['vendor_id' => $vendor_id, 'service_id' => $request->service_id, 'id' => $request->id];
        $validator = Validator::make($request->all(), [
            'service_id' => [
                'required',
                Rule::unique('vendor_services')->where(function ($query) use ($validateParams) {
                    return $query->where('vendor_id', $validateParams['vendor_id'])
                        ->where('service_id', $validateParams['service_id'])
                        ->where('id', '!=', $validateParams['id']);
                }),
            ],
            'price' => 'required',
            'offer_price' => "less_than_price:price",
            'slot_hour' => "required",
            'slot_minutes' => "required",
        ], [
            'service_id.unique' => 'The service ID is already taken  .',
            'offer_price.less_than_price' => "The offer price must be less than the {$request->price}.",
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    	if ($request->hasFile('service_image')) {
            $file = $request->file('service_image');
            $name = time() . str_shuffle(time()) . '.' . $file->extension();
            $path = public_path() . '/uploads/vendor_service/';
            $file->move($path, $name);
        }else{
        	$name = NULL;
        } 
        $newData = VendorService::find($request->id);
        $newData->service_id = $request->service_id;
        $newData->price = $request->price;
        $newData->offer_price = $request->offer_price;
		$newData->slot_hour = $request->slot_hour;
        $newData->slot_minutes = $request->slot_minutes;
        $newData->updated_at = date('Y-m-d H:i:s');
        $newData->updated_by = \Auth::user()->id;
    	$newData->service_image = $name;
        $newData->save();
        return response()->json([
            'result' => true,
            'message' => "Updated Successfully",
        ]);
    }
	public function deleteService(Request $request)
	{  
        $vendor_id = Vendor::getVendorId(); 
		$data = VendorService::where(['vendor_id' => $vendor_id])->find($request->id);
		if (isset($data)) {
			$data->delete();
			return response()->json([
				'result' => true,
			]);
		} else {
			return response()->json([
				'result' => false,
				'message' => 'Record not found',
			]);
		}

	}

	//book
	public function getBookings()
	{
		$vendor_id = Vendor::getVendorId();
		$data=Booking::with(['pet_profile'])->where('vendor_id',$vendor_id)->get();
		return response()->json([
				'result' => true,
				'data' => $data,
		]);
	}
	public function bookingStatusChange(Request $request)
	{
		$data=Booking::find($request->id);
		$data->status=$request->status;
		$data->save();
		return response()->json([
				'result' => true,
		]);
	}

 	public function addVendorSetting(Request $request)
	{   
    $validator = \Validator::make($request->all(), [
      //'email' => 'string|email|max:255|unique:users,email,' . auth()->user()->id, 
      //'vendor_id' => 'required',
      'label' => 'required',
      'value' => 'required',
      //'mobile' => 'unique:users,mobile,' . auth()->user()->id, 
    ]);

    if ($validator->fails()) {
      $errors = $validator->errors();
      $errors = implode(" ", $errors->all());
      return response()->json([
        'message' => $errors,
      ], 422);
    }    
    $vendor = Vendor::where('user_id', auth()->user()->id)->first();
    //dd($vendor);
    $newVendorSetting = new VendorSetting();
    $newVendorSetting->user_id = auth()->user()->id;
    $newVendorSetting->vendor_id = $vendor->id;
    $newVendorSetting->label = $request->label;
    $newVendorSetting->value = $request->value;
    $newVendorSetting->save(); 
    $vendor_setting = VendorSetting::where('id', $newVendorSetting->id)->first();
    
    return response()->json([
      'result' => true,
      'data' => $vendor_setting,
    ]);
}
}
