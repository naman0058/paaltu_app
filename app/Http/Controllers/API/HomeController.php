<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PetProfile;
use App\Models\PetGallery;
use App\Models\Follow;
use App\Models\Service;
use App\Models\VendorService;
use App\Models\Breed;
use App\Models\PetCategory;
use App\Models\Booking;
use App\Models\Vendor; 
use App\Models\VendorSetting; 
use Validator;
use Carbon\Carbon;
class HomeController extends BaseController
{
	public function getBreeds(Request $request)
	{
		$data=Breed::select('*');
		if($request->has('pet_category_id') && $request->pet_category_id != '')
		{
			$data=$data->where('pet_category_id',$request->pet_category_id);
		}
		$data=$data->get();	
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function getCategories(Request $request)
	{
		$data=PetCategory::all();
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function getPets(Request $request)
	{
		if(PetProfile::where('user_id',auth()->user()->id)->count() == 0)
		{
			return response()->json([
			'result'=>false,
			'error'=>'Complete pet profile and continue ! '
			]);
		}
		if($request->has('lat') && $request->lat && $request->has('lng') && $request->lng)
		{
			$petProfile=PetProfile::where('user_id',auth()->user()->id)->first();
			if(isset($petProfile))
			{
				$petProfile=$request->lat;
				$petProfile=$request->lng;
				$petProfile->save();
			}
		}
		// $user=User::select('users.id','pet_profile.lat','pet_profile.lng')
		// ->leftJoin('pet_profile','pet_profile.user_id','users.id')
		// ->where('users.id',auth()->user()->id)
		// ->first();
		 
		$latitude=$request->lat;
		$longitude=$request->lat;
		$data=PetProfile::select('pet_profile.*','pet_profile.user_id as pet_user_id')->with(['user','photos','category','breed']);
		if($request->has('distance') && $request->distance != '' && ($latitude != null && $longitude != null))
		{
			$data=$data->selectRaw('*, ( 6371 * acos( cos( radians(?) ) *
					cos( radians( latitude ) )
					* cos( radians( longitude ) - radians(?)
					) + sin( radians(?) ) *
					sin( radians( latitude ) ) )
					) AS distance', [$latitude, $longitude, $latitude])
				->having('distance', '<=', $request->distance);
		}
		if($request->has('breed_id') && is_array($request->breed_id))
		{
			$data=$data->whereIn('pet_profile.breed_id',$request->breed_id);
		}
		if($request->has('pet_category_id') && is_array($request->pet_category_id))
		{
			$data=$data->whereIn('pet_profile.pet_category_id',$request->pet_category_id);
		}
		if($request->has('gender') && $request->gender != '')
		{
			$data=$data->where('pet_profile.gender',$request->gender);
		}
		if($request->has('age_from') && $request->has('age_to') && $request->input('age_from') != '' && $request->input('age_to') != '')
		{
			$fromAge = $request->input('age_from');
			$toAge = $request->input('age_to');
			$dateColumn = 'date_of_birth';

			$fromDate = Carbon::now()->subYears($toAge)->startOfYear();
			$toDate = Carbon::now()->subYears($fromAge)->endOfYear();
			$data=$data->whereBetween($dateColumn, [$fromDate, $toDate]);
		}
		$data=$data->where('pet_profile.user_id','!=',auth()->user()->id)->get();
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function follow(Request $request)
	{
		
		$data=Follow::firstOrCreate([
			'user_id'=>auth()->user()->id,
			'pet_user_id'=>$request->pet_user_id
		],
		[
			'user_id'=>auth()->user()->id,
			'pet_user_id'=>$request->pet_user_id,
			'created_at'=>date('Y-m-d H:i:s')
		]);
		return response()->json([
			'result'=>true,
		]);
	}
	public function getRequests()
	{
		$data=Follow::where([
			'pet_user_id'=>auth()->user()->id,
			'status'=>'pending'
		])->get();
	 
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function confirmRequest(Request $request)
	{
		$validator = \Validator::make($request->all(), [
        	'id' => 'required|exists:follow,id',
        	'status' => 'required',
    	]);
		if ($validator->fails()) {
			$errors = $validator->errors();
                $errors = implode(" ", $errors->all());
                return response()->json([
                    'message' => $errors,
                ], 422);
		}
		$data=Follow::find($request->id);
		//dd($data);
		$data->status=$request->status == 'accept' ? 'accept' :'reject';
	 	if($request->status == 'accept')
		{
			//creating another row 
			$follow=Follow::where('user_id',auth()->user()->id)
			->where('pet_user_id',$data->user_id)
			->where('status','pending')
			->first();
			if(!isset($follow))
			{
				$follow=new Follow();
				$follow->user_id=auth()->user()->id;
				$follow->pet_user_id=$data->user_id;
				$follow->created_at=date('Y-m-d H:i:s');
				$follow->created_at=date('Y-m-d H:i:s');
			}
			$follow->status='accept';
			$follow->save();
		}
		$data->save();
		return response()->json([
			'result'=>true,
		]);
	}
	//services
	public function getServices(Request $request)
	{
		// 'vendor_services'=>function($q){
		// 	$q->select('vendor_id','service_id');
		// },
		$data=Vendor::with(['vendor_services']);
		//dd($data);
		if($request->has('service_id') && $request->service_id != '')
		{
			$data=$data->whereHas('vendor_services',function($q)use($request){
				$q->where('vendor_services.service_id',$request->service_id);
			});
			//return response()->json([$data->toSql()]);
		}
		$data=$data->get();
		//$data
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function getService($id)
	{
		$data=VendorService::with('service')->find($id);
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function getVendor($id)
	{
		$data=Vendor::with(['vendor_service','vendor_service.service'])->find($id);

		if (isset($data)) {
            $data = $data->makeHidden([
                'created_by',
                'updated_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);
		 }
		return response()->json([
			'result'=>true,
			'data'=>$data
		]);
	}
	public function getAvailableSessions(Request $request)
	{
		// Get the desired time range
		$from = date('H:i:s',strtotime(VendorSetting::getValue('OPENING_TIME',$request->vendor_id)));
		$to = date('H:i:s',strtotime(VendorSetting::getValue('CLOSING_TIME',$request->vendor_id)));
		$vendor_service=VendorService::find($request->vendor_service_id);
		$totalSeconds = $vendor_service->slot_hour * 3600 + $vendor_service->slot_minutes * 60;
		//$to=date('H:i',strtotime($to)-$totalSeconds);
		// Query the database to get all booked sessions within the desired time range
		$bookings = Booking::where(function ($query) use ($from, $to) {
				$query->whereBetween('session_from', [$from, $to])
					->orWhereBetween('session_to', [$from, $to]);
			})
			->where('vendor_id',$request->vendor_id)
			->whereDate('date',date('Y-m-d',strtotime($request->date)))
			->get()
			->pluck('session_from', 'session_to')
			->toArray();
			
		
		// Create an array of all possible sessions within the desired time range
		$sessions = [];
		$sessionsTemp = [];
		$currentSession =date('H:i',strtotime($from));
		while ($currentSession < $to) {
			$sessions[] = "{$currentSession} - ".date('H:i',strtotime($currentSession)+$totalSeconds);
			$sessionsTemp[] =$currentSession;
			$currentSession=date('H:i',strtotime($currentSession)+$totalSeconds);
		}
		$arr=[];
		 
		foreach($bookings as $key=>$booking)
		{
			$arr[date('H:i',strtotime($booking))]=date('H:i',strtotime($key));
		}
		$bookings=$arr;
		// Remove booked sessions from the array to get the list of available sessions
		foreach ($bookings as $bookedFrom => $bookedTo) {
			foreach ($sessionsTemp as $key => $session) {
				if ($session >= $bookedFrom && $session < $bookedTo) {
					unset($sessions[$key]);
				}
				if($request->date == date('Y-m-d') && $session <= date('H:i'))
				{
					unset($sessions[$key]);
				}
			}
		}
		return response()->json([
			'result'=>true,
			'data'=>$sessions
		]);
	}
	public function bookService(Request $request)
	{
		//'unique:vendor_bookings,vendor_service_id,NULL,id,vendor_id,' . request('vendor_id') . ',slot,' . request('slot') . ',date,' . request('date'),
		  
		$validator = \Validator::make($request->all(), [
         	'vendor_service_id' => 'required',
         	'vendor_id' => 'required',
        	'slot' => 'required',
        	'date' => 'required',
			//'unique:vendor_bookings,vendor_service_id,NULL,id,vendor_id,' . request('vendor_id') . ',slot,' . request('slot') . ',date,' . request('date'),
    	]);
		if ($validator->fails()) {
			$errors = $validator->errors();
                $errors = implode(" ", $errors->all());
                return response()->json([
                    'message' => $errors,
                ], 422);
		}
		$slot=explode('-',$request->slot);
		$data=Booking::create([
			'vendor_service_id'=>$request->vendor_service_id,
			'vendor_id'=>$request->vendor_id,
			'date'=>$request->date,
			'booking_no'=>date('ymdHis').rand(1000,9999),
			'pet_user_id'=>auth()->user()->id,
			'session_from'=>trim($slot[0]),
			'session_to'=>trim($slot[1]),
			'created_at'=>date('Y-m-d H:i:s'),
			'status'=>'pending'
		]);
		return response()->json([
			'result'=>true ,
			'message'=>"Booked successfully "
		]);
	}
	public function getBookings(Request $request)
	{
		$data=Booking::with('service')->orderBy('created_at','desc')->get();
		return response()->json([
			'result'=>true ,
			'data'=>$data
		]);
	}
	public function getBooking(Request $request)
	{ 
		$vendor=[];
		$data=Booking::with('service')->find($request->id);
		if(isset($data->service) && isset($data->service->vendor_id))
		{
			$vendor=Vendor::find($data->service->vendor_id);
		}
		return response()->json([
			'result'=>true,
			'data'=>$data,
			'vendor'=>$vendor
		]);
	}
	public function editProfile(Request $request)
	{
		
		$validator = \Validator::make($request->all(), [
         	'email' => 'required|string|email|max:255|unique:users,email,'.auth()->user()->id,
        	//'username' => 'required|string|max:255|unique:users,username,'.auth()->user()->id,
        	'name' => 'required',
        	'address' => 'required',
        	'mobile' => 'required',
    	]);
		if ($validator->fails()) {
                $errors = $validator->errors();
                $errors = implode(" ", $errors->all());
                return response()->json([
                    'message' => $errors,
                ], 422);
            }
		// if ($validator->fails()) {
		// 	return response()->json([
		// 		'result'=>false,
		// 		'message'=>$validator->errors()
		// 	],422);
		// }
		$thisData=[];
		if($request->hasFile('file'))
		{	
			$file=$request->file('file');
			$name=time().str_shuffle(time()).'.'.$file->extension();
			$path=public_path().'/'.'users'; 
			$file->move($path,$name);
			$thisData=[
				'name'=>$request->name,
				'username'=>$request->username,
				'email'=>$request->email,
				'address'=>$request->address,
				'dp'=>$name,
				'mobile'=>$request->mobile,
            	'alt_mobile' => $request->alt_mobile
			];
			 
		}else{ 
			$thisData=[
				'name'=>$request->name,
				'username'=>$request->username,
				'email'=>$request->email,
				'address'=>$request->address,
				'mobile'=>$request->mobile,
            	'alt_mobile' => $request->alt_mobile
			];
		} 
		$data = User::find(auth()->user()->id)
		->update($thisData);
		return response()->json([
			'result'=>true,
			'data'=> User::where('id', auth()->user()->id)->first()
		]);
	}

	public function getUserProfile()
	{ 
		$data = User::find(auth()->user()->id)
		->first();
		return response()->json([
			'result'=>true,
			'data'=> User::where('id', auth()->user()->id)->first()
		]);
	}
	public function editPet(Request $request)
	{
		$validator = \Validator::make($request->all(), [
        	'pet_id' => 'required|exists:pet_profile,id',
        	'pet_name' => 'required',
        	'breed_id' => 'required',
        	'pet_category_id' => 'required',
        	'date_of_birth' => 'required',
        	'about' => 'required',
        	'date_of_birth' => 'required',
        	'place' => 'required',
    	]);
		if ($validator->fails()) {
			$errors = $validator->errors();
            $errors = implode(" ", $errors->all());
            return response()->json([
                'message' => $errors,
            ], 422);
		}
		$thisData=[
			'pet_name'=>$request->pet_name,
			'breed_id'=>$request->breed_id,
			'pet_category_id'=>$request->pet_category_id,
			'date_of_birth'=>$request->date_of_birth,
			'gender'=>$request->gender,
			'place'=>$request->place,
			'lat'=>$request->lat,
			'lng'=>$request->lng,
			'about'=>$request->about,
			'updated_at'=>date('Y-m-d H:i:s'), 
			'updated_by'=>auth()->user()->id, 
		];
		$data=PetProfile::where(['user_id' => auth()->user()->id, 'id' => $request->pet_id])
		->first()
		->update($thisData);
		if($request->hasFile('image'))
		{ 
			$image_name = time().str_shuffle(time()).'_image.'.$request->image->extension();
			$path=public_path('images/pet/files/');
			$request->image->move($path,$image_name);
			
			PetProfile::where('id', $request->pet_id)->update([
				'image' => $image_name, 
			]);

			// $pg=new PetGallery();
			// $pg->pet_id = $request->pet_id;
			// $pg->image = $image_name;
			// $pg->save(); 
		}
		if($request->hasFile('image_one'))
		{ 
			$image_one_name = time().str_shuffle(time()).'_image_one.'.$request->image_one->extension();
			$path=public_path('images/pet/files/');
			$request->image_one->move($path,$image_one_name);
			
			PetProfile::where('id', $request->pet_id)->update([
				'image_one' => $image_one_name, 
			]);

			// $pg=new PetGallery();
			// $pg->pet_id = $request->pet_id;
			// $pg->image = $image_one_name;
			// $pg->save(); 
		}
		if($request->hasFile('image_two'))
		{ 
			$image_two_name = time().str_shuffle(time()).'_image_two.'.$request->image_two->extension();
			$path=public_path('images/pet/files/');
			$request->image_two->move($path,$image_two_name);
			
			PetProfile::where('id', $request->pet_id)->update([
				'image_two' => $image_two_name, 
			]);

			// $pg=new PetGallery();
			// $pg->pet_id = $request->pet_id;
			// $pg->image = $image_two_name;
			// $pg->save(); 
		}

		if($request->hasFile('image_three'))
		{ 
			$image_three_name = time().str_shuffle(time()).'_image_three.'.$request->image_three->extension();
			$path=public_path('images/pet/files/');
			$request->image_three->move($path,$image_three_name);
			
			PetProfile::where('id', $request->pet_id)->update([
				'image_three' => $image_three_name, 
			]);

			// $pg=new PetGallery();
			// $pg->pet_id = $request->pet_id;
			// $pg->image = $image_three_name;
			// $pg->save(); 
		}

		if($request->hasFile('image_four'))
		{ 
			$image_four_name = time().str_shuffle(time()).'_image_four.'.$request->image_four->extension();
			$path=public_path('images/pet/files/');
			$request->image_four->move($path,$image_four_name);
			
			PetProfile::where('id', $request->pet_id)->update([
				'image_four' => $image_four_name, 
			]);

			// $pg=new PetGallery();
			// $pg->pet_id = $request->pet_id;
			// $pg->image = $image_four_name;
			// $pg->save(); 
		}
		if($request->hasFile('video')){ 
            $filenameWithExt= $request->file('video')->getClientOriginalName(); 
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME); 
            $extension = $request->file('video')->getClientOriginalExtension();
            $fileNameToStore = $filename. '_'.time().'.'.$extension; 
            $path = public_path('images/pet/files/video/');
            $request->file('video')->move($path,$fileNameToStore); 
            PetProfile::where('id', $request->pet_id)->update([
				'video' => $fileNameToStore, 
			]);
        }   
            $pet_profile_data = PetProfile::where(['user_id' => auth()->user()->id, 'id' => $request->pet_id])->first(); 
            $pet_profile_data->image = url('/public/images/pet/files/').'/'.$pet_profile_data->image;
            $pet_profile_data->image_one = url('/public/images/pet/files/').'/'.$pet_profile_data->image_one;
            $pet_profile_data->image_two = url('/public/images/pet/files/').'/'.$pet_profile_data->image_two;
            $pet_profile_data->image_three = url('/public/images/pet/files/').'/'.$pet_profile_data->image_three;
            $pet_profile_data->image_four = url('/public/images/pet/files/').'/'.$pet_profile_data->image_four;
            $pet_profile_data->video = url('/public/images/pet/files/video/').'/'.$pet_profile_data->video;
            return response()->json([
                'result'=>true,
                'data'=> $pet_profile_data
            ]);
		// return response()->json([
		// 	'result'=>true,
		// 	'data'=>$show_data
		// ]);
	}
	public function uploadPetPhoto(Request $request)
	{	
		$validator = \Validator::make($request->all(), [
        	'pet_id' => 'required|exists:pet_profile,id', 
        	'file' => 'required',
    	]);
		if ($validator->fails()) {
			$errors = $validator->errors();
            $errors = implode(" ", $errors->all());
            return response()->json([
                'message' => $errors,
            ], 422);
		}
		$pet = PetProfile::where(['user_id' => auth()->user()->id])->first();

		if($request->hasFile('file') && isset($pet))
		{	
			$file=$request->file('file');
			$name=time().str_shuffle(time()).'.'.$file->extension();
			$path=public_path('images/pet/files/');
			$file->move($path,$name);

			$data=new PetGallery();
			$data->pet_id=$pet->id;
			$data->image=$name;
			$data->save();

			return response()->json([
				'result'=>true,
				'image'=>asset('public/images/pet/files/'.$name),
				'id'=>$data->id
			]);
		}
		return response()->json([
			'message'=>'Add Pet First',
		],203);
	}
	public function deletePetPhoto(Request $request)
	{
		$data=PetGallery::findOrFail($request->id); 
		if(isset($data)){
			$path=public_path('public/images/pet/files/'.$data->image);
			if(file_exists($path) && $data->image != ''){
				unlink($path);
			}
			$data->delete();
			return response()->json([
				'result'=>true,
			]);
		}
		return response()->json([
				'result'=>false,
			]);
	}
	public function getPetPhotos(Request $request)
	{
		$data=PetProfile::where('user_id',auth()->user()->id)->first();
		$datas=PetGallery::where('pet_id',$data->id)->get();
		return response()->json([
				'result'=>true,
				'data'=>$datas
			]);
	}
	public function getUser()
	{
		$is_profile_added=PetProfile::where('user_id',auth()->user()->id)->count();
		$is_profile_added= $is_profile_added == 0 ? false : true ; 
		return response()->json([
				'result'=>true,
				'data'=>auth()->user(),
				'is_profile_added'=>$is_profile_added

			]);
	}
	public function getPetsProfile()
	{
		$data=PetProfile::select('pet_profile.*','pet_profile.user_id as pet_user_id')->with(['user','photos','category','breed'])
		->where('pet_profile.user_id',auth()->user()->id)
		->get();
		return response()->json([
				'result'=>true,
				'data'=>$data
		]);
	}

	public function getPet(Request $request)
	{
		$data=PetProfile::select('pet_profile.*','pet_profile.user_id as pet_user_id')->with(['user','photos','category','breed'])
		->where('pet_profile.id',$request->id)
		->first();
		return response()->json([
				'result'=>true,
				'data'=>$data
		]);
	}

	public function addPet(Request $request)
	{
		$validator = \Validator::make($request->all(), [
        	'pet_name' => 'required',
        	'breed_id' => 'required',
        	'pet_category_id' => 'required',
        	'date_of_birth' => 'required',
        	'about' => 'required', 
        	'place' => 'required',
        	'primary_pet' => 'required|numeric'
    	]);

		if ($validator->fails()) {
			$errors = $validator->errors();
            $errors = implode(" ", $errors->all());
            return response()->json([
                'message' => $errors,
            ], 422);
		}

		$thisData=[
			'pet_name'=>$request->pet_name,
			'breed_id'=>$request->breed_id,
			'pet_category_id'=>$request->pet_category_id,
			'date_of_birth'=>$request->date_of_birth,
			'gender'=>$request->gender,
			'place'=>$request->place,
			'lat'=>$request->lat,
			'lng'=>$request->lng,
			'about'=>$request->about,
			'created_at'=>date('Y-m-d H:i:s'),
			'updated_at'=>date('Y-m-d H:i:s'),
			'created_by'=>auth()->user()->id,
			'updated_by'=>auth()->user()->id,
			'user_id'=>auth()->user()->id,
			'primary_pet' => $request->primary_pet,
		]; 
		$pet_count = PetProfile::where('user_id', auth()->user()->id)->count(); 
		if($pet_count >= 3){
			return response()->json([
				'message'=>'You can not add more than 3 pet.',
			],203);
		}else{
			$data=new PetProfile($thisData);
			$data->save();

 			if($request->hasFile('image'))
			{ 
				$image_name = time().str_shuffle(time()).'_image.'.$request->image->extension();
				$path=public_path('images/pet/files/');
				$request->image->move($path,$image_name);
				
				PetProfile::where('id', $data->id)->update([
					'image' => $image_name, 
				]);

				$pg=new PetGallery();
				$pg->pet_id = $data->id;
				$pg->image = $image_name;
				$pg->save(); 
			}
			if($request->hasFile('image_one'))
			{ 
				$image_one_name = time().str_shuffle(time()).'_image_one.'.$request->image_one->extension();
				$path=public_path('images/pet/files/');
				$request->image_one->move($path,$image_one_name);
				
				PetProfile::where('id', $data->id)->update([
					'image_one' => $image_one_name, 
				]);

				$pg=new PetGallery();
				$pg->pet_id = $data->id;
				$pg->image = $image_one_name;
				$pg->save(); 
			}
			if($request->hasFile('image_two'))
			{ 
				$image_two_name = time().str_shuffle(time()).'_image_two.'.$request->image_two->extension();
				$path=public_path('images/pet/files/');
				$request->image_two->move($path,$image_two_name);
				
				PetProfile::where('id', $data->id)->update([
					'image_two' => $image_two_name, 
				]);

				$pg=new PetGallery();
				$pg->pet_id = $data->id;
				$pg->image = $image_two_name;
				$pg->save(); 
			}

			if($request->hasFile('image_three'))
			{ 
				$image_three_name = time().str_shuffle(time()).'_image_three.'.$request->image_three->extension();
				$path=public_path('images/pet/files/');
				$request->image_three->move($path,$image_three_name);
				
				PetProfile::where('id', $data->id)->update([
					'image_three' => $image_three_name, 
				]);

				$pg=new PetGallery();
				$pg->pet_id = $data->id;
				$pg->image = $image_three_name;
				$pg->save(); 
			}

			if($request->hasFile('image_four'))
			{ 
				$image_four_name = time().str_shuffle(time()).'_image_four.'.$request->image_four->extension();
				$path=public_path('images/pet/files/');
				$request->image_four->move($path,$image_four_name);
				
				PetProfile::where('id', $data->id)->update([
					'image_four' => $image_four_name, 
				]);

				$pg=new PetGallery();
				$pg->pet_id = $data->id;
				$pg->image = $image_four_name;
				$pg->save(); 
			}
			if($request->hasFile('video')){ 
	            $filenameWithExt= $request->file('video')->getClientOriginalName();
	            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
	            $extension = $request->file('video')->getClientOriginalExtension();
	            $fileNameToStore = $filename. '_'.time().'.'.$extension;
            	$path = public_path('images/pet/files/video/');
            	$request->file('video')->move($path,$fileNameToStore); 
            	PetProfile::where('id', $request->pet_id)->update([
                	'video' => $fileNameToStore, 
            	]);
				// $path = $request->file('video')->move(public_path('images/pet/files/video/'),$fileNameToStore); 
				// PetProfile::where('id', $data->id)->update([
				// 	'video' => $path, 
				// ]);
	        }  
			$user=User::find(auth()->user()->id)->update(['is_profile_added',1]);
			$pet_profile_data = PetProfile::where('id',$data->id)->first();
        	//$pet_profile_data = PetProfile::where(['user_id' => auth()->user()->id, 'id' => $request->pet_id])->first(); 
            $pet_profile_data->image = url('/public/images/pet/files/').'/'.$pet_profile_data->image;
            $pet_profile_data->image_one = url('/public/images/pet/files/').'/'.$pet_profile_data->image_one;
            $pet_profile_data->image_two = url('/public/images/pet/files/').'/'.$pet_profile_data->image_two;
            $pet_profile_data->image_three = url('/public/images/pet/files/').'/'.$pet_profile_data->image_three;
            $pet_profile_data->image_four = url('/public/images/pet/files/').'/'.$pet_profile_data->image_four;
            $pet_profile_data->video = url('/public/images/pet/files/video/').'/'.$pet_profile_data->video;
			return response()->json([
				'result'=>true,
				'data'=> $pet_profile_data
			]);
		} 
	}

	public function updatePrimaryPet(Request $request)
	{
		$validator = \Validator::make($request->all(), [
        	'id' => 'required', 
        	//'user_id' => 'required', 
        	'primary_pet' => 'required',
    	]);
		if ($validator->fails()) {
			$errors = $validator->errors();
                $errors = implode(" ", $errors->all());
                return response()->json([
                    'message' => $errors,
                ], 422);
		}
		$thisData=[
			'id'=>$request->id,
			//'user_id'=>$request->user_id,
			'primary_pet'=>$request->primary_pet, 
		];
		$primary_pet_count = PetProfile::where(['user_id' => auth()->user()->id, 'primary_pet' => 1])->count();
		if($primary_pet_count >= 1){
			PetProfile::where(['user_id' => auth()->user()->id, 'primary_pet' => 1])->update([
				'primary_pet' => 0
			]);
			if($request->primary_pet == 1){
				$data = PetProfile::where('id',$request->id) 
				->update([
					'primary_pet' => $request->primary_pet
				]);
				return response()->json([
					'result'=>true,
					'data'=> PetProfile::where('id',$request->id)->first()
				]);
			}else{
				$data = PetProfile::where(['user_id' => auth()->user()->id])->orderBy('id','asc')->first();  
				$data->primary_pet = 1;
				$data->save(); 
				return response()->json([
					'result'=>true,
					'data'=> PetProfile::where('id',$request->id)->first()
				]);
			}
		}else{
			// $data = PetProfile::where('id',$request->id) 
			// ->update([
			// 	'primary_pet' => $request->primary_pet
			// ]);
			// return response()->json([
			// 	'result'=>true,
			// 	'data'=> PetProfile::where('id',$request->id)->first()
			// ]);
			if($request->primary_pet == 1){
				$data = PetProfile::where('id',$request->id) 
				->update([
					'primary_pet' => $request->primary_pet
				]);
				return response()->json([
					'result'=>true,
					'data'=> PetProfile::where('id',$request->id)->first()
				]);
			}else{
				$data = PetProfile::where(['user_id' => auth()->user()->id])->orderBy('id','asc')->first();  
				$data->primary_pet = 1;
				$data->save(); 
				return response()->json([
					'result'=>true,
					'data'=> PetProfile::where('id',$request->id)->first()
				]);
			}
		}
	}

	public function deletePet(Request $request)
	{
		$data=PetProfile::findOrFail($request->id); 
		if(isset($data)){
        	if($data->image_one){
				$path_one=public_path('public/images/pet/files/'.$data->image_one);
				if(file_exists($path_one) && $data->image != ''){
					unlink($path_one);
				}
            }
        	if($data->image_two){
        		$path_two=public_path('public/images/pet/files/'.$data->image_two);
				if(file_exists($path_two) && $data->image != ''){
					unlink($path_two);
				}
            }
            if($data->image_three){
        		$path_three=public_path('public/images/pet/files/'.$data->image_three);
				if(file_exists($path_three) && $data->image != ''){
					unlink($path_three);
				}
            }
            if($data->image_four){
        		$path_four=public_path('public/images/pet/files/'.$data->image_four);
				if(file_exists($path_four) && $data->image != ''){
					unlink($path_four);
				}
            }
            if($data->video){
        		$path_video=public_path('public/images/pet/files/'.$data->video);
				if(file_exists($path_video) && $data->image != ''){
					unlink($path_video);
				}
            }
			$data->delete();
			return response()->json([
				'result'=>true,
			]);
		}
		return response()->json([
                'result'=>false,
            ]);
	}
	 
}