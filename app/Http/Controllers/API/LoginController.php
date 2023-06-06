<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PetProfile;
use App\Models\Vendor;
use App\Models\VendorSetting;
use Validator;
use Hash;
use Auth;
class LoginController extends BaseController
{
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required',
			'password' => 'required',
		]); 
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());       
		} 
		$data = [
			'email' => $request->email,
			'password' => $request->password
		]; 
		if (auth()->attempt($data)) { 
			$token = auth()->user()->createToken('paaltu')->accessToken;
			if(auth()->user()->user_type == 'user')
			{
				$is_profile_added=PetProfile::where('user_id',auth()->user()->id)->count();
				$is_profile_added=$is_profile_added == 0 ? false : true ;
				$json = array(
					'status' => true,
					'token'=>$token,
					'user_id'=>auth()->user()->id,
					'is_profile_added'=>$is_profile_added,
					'user'=> \Auth::user(),
					'message' => 'Login Successful.'
				);
				return response()->json($json, 200); 
			}else{
				$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
				$is_vendor_added=$is_vendor_added == 0 ? false : true ;
				$json = array(
					'status' => true,
					'token'=>$token,
					'user_id'=>auth()->user()->id,
					'is_vendor_added'=>$is_vendor_added,
					'user'=> \Auth::user(),
					'message' => 'Login Successful.'
				);
				return response()->json($json, 200);
			} 
		} else { 
			return response()->json(['error' => 'Unauthorized'], 401);
		}
	} 

	public function logout(Request $request)
	{	 
		$request->user()->token()->revoke();
		return response()->json([
			'message' => 'Successfully logged out'
		]);
	}

	public function signup(Request $request)
	{
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'username' => 'required|unique:users',
			'email' => 'required|unique:users',
			'mobile' => 'required|unique:users',
			'password' => 'required|min:6',
			'user_type' => ['required', Rule::in(['user', 'vendor'])]
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		$user=new User();
		$user->name=$request->name;
		$user->username=$request->username;
		$user->user_type=$request->user_type; //user,vendor
		$user->email=$request->email;
		$user->mobile=$request->mobile;
		$user->password=bcrypt($request->password);
		$user->save();
		if($request->has('user_type') && $request->user_type == 'vendor')
		{
			$vendor = Vendor::where('user_id', $user->id)->first();
			if (!isset($vendor)) {
				$vendor = new Vendor();
			}
			$vendor->user_id = $user->id;
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
				if(VendorSetting::where(['user_id'=>$user->id,'label'=>$key])->count()==0)
				{
					$newVendorSetting=new VendorSetting();
					$newVendorSetting->user_id=$user->id;
					$newVendorSetting->vendor_id=$vendor->id;
					$newVendorSetting->label=$key;
					$newVendorSetting->value=$item;
					$newVendorSetting->save();
				}
			}
		}
		return response()->json([
			'result'=>true,
			'message' => 'Successfully Created',
		]);
	}

	public function changePassword(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'old_password' => 'required',
			'new_password' => 'required',
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}
		$user = auth()->user();
		if (Hash::check($request->old_password, $user->password))
		{
			$user->password = bcrypt($request->new_password);
			$user->save();
			return response()->json([
				'result'=>true,
			]);
		}
		return response()->json([
			'result'=>false,'errors'=>['old_password'=>'Incorrect Password']
		]);
	}

	public function vendorSignUp(Request $request)
	{	
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [ 
			'username' => 'required', 
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		$otp = random_int(100000, 999999); 
		if (is_numeric($request->get('username'))) { 
            $validator_chk = Validator::make($request->all(), [ 
				'username' => 'required|unique:users,mobile'
			]);

			if($validator_chk->fails()){
				return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
			}

			$username = $request->get('username'); 
			$password=substr(str_shuffle('0123456789ABCDEFGHI'),0,6);

			$user = new User(); 
			$user->user_type = 'vendor';  
			$user->mobile=$username; 
			$user->password=bcrypt($password);
			$user->otp = $otp; 
			$user->save();

			$vendor = Vendor::where('user_id', $user->id)->first();
			if (!isset($vendor)) {
				$vendor = new Vendor();
			}
			$vendor->user_id = $user->id; 
			$vendor->mobile = $username; 
			$vendor->save(); 
        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
            $validator_chk = Validator::make($request->all(), [ 
				'username' => 'required|email|unique:users,email'
			]);

			if($validator_chk->fails()){
				return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
			}

			$username = $request->get('username'); 
			$password=substr(str_shuffle('0123456789ABCDEFGHI'),0,6);
			$user = new User(); 
			$user->user_type = 'vendor'; 
			$user->email=$username; 
			$user->password=bcrypt($password);
			$user->otp= $otp; 
			$user->save();

			$vendor = Vendor::where('user_id', $user->id)->first();
			if (!isset($vendor)) {
				$vendor = new Vendor();
			}
			$vendor->user_id = $user->id; 
			$vendor->email = $username; 
			$vendor->save();
        } 

		if($user->user_type == 'vendor')
		{ 
			//setting for vendor
			$settingsArray=['OPENING_TIME'=>'09:00','CLOSING_TIME'=>'17:00'];
			foreach($settingsArray as $key=>$item)
			{
				if(VendorSetting::where(['user_id'=>$user->id,'label'=>$key])->count()==0)
				{
					$newVendorSetting=new VendorSetting();
					$newVendorSetting->user_id=$user->id;
					$newVendorSetting->vendor_id=$vendor->id;
					$newVendorSetting->label=$key;
					$newVendorSetting->value=$item;
					$newVendorSetting->save();
				}
			}
		}
		return response()->json([
			'result'=>true,
			'otp'=>$otp,
			'message' => 'Successfully Created',
		]);
	} 

	public function vendorOtpVerify(Request $request)
	{	
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [ 
			'username' => 'required', 
			'otp' => 'required', 
			'type' => 'required', 
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		if($request->type == 'register'){
			if (is_numeric($request->get('username'))) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|exists:users,mobile' 
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('mobile', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save(); 
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
						$is_vendor_added=$is_vendor_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_vendor_added'=>$is_vendor_added,
							'user'=> \Auth::user(),
							'message' => 'Account successfully verify.'
						);
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}elseif(empty($user->otp)){
					// return response()->json([
					// 	'result'=>true, 
					// 	'message' => 'account already verify',
					// ]);
					$user_login = Auth::login($user);  
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
						$is_vendor_added=$is_vendor_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_vendor_added'=>$is_vendor_added,
							'user'=> \Auth::user(),
							'message' => 'Account already verify.'
						);
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				}  
	        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|email|exists:users,email'
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('email', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save(); 
					$user_login = Auth::login($user);  
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
						$is_vendor_added=$is_vendor_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_vendor_added'=>$is_vendor_added,
							'user'=> \Auth::user(),
							'message' => 'Account successfully verify.'
						);
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}elseif(empty($user->otp)){ 
					// return response()->json([
					// 	'result'=>true, 
					// 	'message' => 'account already verify',
					// ]);
					$user_login = Auth::login($user);  
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
						$is_vendor_added=$is_vendor_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_vendor_added'=>$is_vendor_added,
							'user'=> \Auth::user(),
							'message' => 'Account already verify.'
						);
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				}
	        }   
	    }else{
	    	if (is_numeric($request->get('username'))) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|exists:users,mobile' 
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('mobile', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save();
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
						$is_vendor_added=$is_vendor_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_vendor_added'=>$is_vendor_added,
							'user'=> \Auth::user(),
							'message' => 'Login Successfully.'
						);
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				} 
	        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|email|exists:users,email'
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('email', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save();
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_vendor_added=Vendor::where('user_id',auth()->user()->id)->count();
						$is_vendor_added=$is_vendor_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_vendor_added'=>$is_vendor_added,
							'user'=> \Auth::user(),
							'message' => 'Login Successfully.'
						);
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				} 
	        } 
	    }
	}

	public function vendorLogin(Request $request)
	{	
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [ 
			'username' => 'required',  
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		$otp = random_int(100000, 999999); 
		if (is_numeric($request->get('username'))) { 
            $validator = Validator::make($request->all(), [ 
				'username' => 'required|exists:users,mobile' 
			]); 

			if($validator->fails()){
				return response()->json(['result'=>false,'errors'=>$validator->errors()]); 
			}

			$username = $request->get('username'); 

			$user = User::where('mobile', $username)->first();
			if($user){
				$user->otp = $otp;
				$user->save();
				return response()->json([
					'result'=>true, 
					'otp'=>$otp,
					'message' => 'otp send successfully',
				]);
			}else{
				return response()->json([
					'result'=>false, 
					'message' => 'not found',
				]);
			} 
        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
            $validator_chk = Validator::make($request->all(), [ 
				'username' => 'required|email|exists:users,email'
			]);

			if($validator_chk->fails()){
				return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
			}

			$username = $request->get('username'); 

			$user = User::where('email', $username)->first();
			if($user){
				$user->otp = $otp;
				$user->save();
				return response()->json([
					'result'=>true, 
					'otp'=>$otp,
					'message' => 'otp send successfully',
				]);
			}else{
				return response()->json([
					'result'=>false, 
					'message' => 'not found',
				]);
			}
        }   
	}  

	public function userSignUp(Request $request)
	{	
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [ 
			'username' => 'required', 
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		$otp = random_int(100000, 999999); 
		if (is_numeric($request->get('username'))) { 
            $validator_chk = Validator::make($request->all(), [ 
				'username' => 'required|unique:users,mobile'
			]);

			if($validator_chk->fails()){
				return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
			}

			$username = $request->get('username'); 
			$password=substr(str_shuffle('0123456789ABCDEFGHI'),0,6);

			$user = new User(); 
			$user->user_type = 'user';  
			$user->mobile=$username; 
			$user->password=bcrypt($password);
			$user->otp = $otp; 
			$user->save(); 
        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
            $validator_chk = Validator::make($request->all(), [ 
				'username' => 'required|email|unique:users,email'
			]);

			if($validator_chk->fails()){
				return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
			}

			$username = $request->get('username'); 
			$password=substr(str_shuffle('0123456789ABCDEFGHI'),0,6);

			$user = new User(); 
			$user->user_type = 'user'; 
			$user->email=$username; 
			$user->password=bcrypt($password);
			$user->otp= $otp; 
			$user->save(); 
        }  
		return response()->json([
			'result'=>true,
			'otp'=>$otp,
			'message' => 'Successfully Created',
		]);
	} 

	public function userOtpVerify(Request $request)
	{	
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [ 
			'username' => 'required', 
			'otp' => 'required', 
			'type' => 'required', 
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		if($request->type == 'register'){
			if (is_numeric($request->get('username'))) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|exists:users,mobile' 
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('mobile', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save(); 
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken; 
						$is_profile_added=PetProfile::where('user_id',auth()->user()->id)->count();
						$is_profile_added=$is_profile_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_profile_added'=>$is_profile_added,
							'user'=> \Auth::user(),
							'message' => 'Account successfully verify.'
						); 
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}elseif(empty($user->otp)){
					return response()->json([
						'result'=>true, 
						'message' => 'account already verify',
					]);
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				}

	        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|email|exists:users,email'
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('email', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save(); 
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken;
						$is_profile_added=PetProfile::where('user_id',auth()->user()->id)->count();
						$is_profile_added=$is_profile_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_profile_added'=>$is_profile_added,
							'user'=> \Auth::user(),
							'message' => 'Account successfully verify.'
						);  
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}elseif(empty($user->otp)){
					return response()->json([
						'result'=>true, 
						'message' => 'account already verify',
					]);
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				}
	        }   
	    }else{
	    	if (is_numeric($request->get('username'))) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|exists:users,mobile' 
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('mobile', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save();
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken;  
						$is_profile_added=PetProfile::where('user_id',auth()->user()->id)->count();
						$is_profile_added=$is_profile_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_profile_added'=>$is_profile_added,
							'user'=> \Auth::user(),
							'message' => 'Login Successfully.'
						);  
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				} 
	        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
	            $validator_chk = Validator::make($request->all(), [ 
					'username' => 'required|email|exists:users,email'
				]);

				if($validator_chk->fails()){
					return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
				}

				$username = $request->get('username'); 

				$user = User::where('email', $username)->first();
				if($user->otp == $request->otp){
					$user->otp = NULL;
					$user->save();
					$user_login = Auth::login($user);   
					if (Auth::check()) {  
						$token = auth()->user()->createToken('paaltu')->accessToken;  
						$is_profile_added=PetProfile::where('user_id',auth()->user()->id)->count();
						$is_profile_added=$is_profile_added == 0 ? false : true ;
						$json = array(
							'status' => true,
							'token'=>$token,
							'user_id'=>auth()->user()->id,
							'is_profile_added'=>$is_profile_added,
							'user'=> \Auth::user(),
							'message' => 'Login Successfully.'
						);  
						return response()->json($json, 200);  
					}else { 
						return response()->json(['error' => 'Unauthorized'], 401);
					} 
				}else{
					return response()->json([
						'result'=>false, 
						'message' => 'otp is incorrect',
					]);
				} 
	        } 
	    }
	}

	public function userLogin(Request $request)
	{	
		\Log::info($request->all()); 
		$validator = Validator::make($request->all(), [ 
			'username' => 'required',  
		]);

		if($validator->fails()){
			return response()->json(['result'=>false,'errors'=>$validator->errors()]);       
		}

		$otp = random_int(100000, 999999); 
		if (is_numeric($request->get('username'))) { 
            $validator = Validator::make($request->all(), [ 
				'username' => 'required|exists:users,mobile' 
			]); 

			if($validator->fails()){
				return response()->json(['result'=>false,'errors'=>$validator->errors()]); 
			}

			$username = $request->get('username'); 

			$user = User::where('mobile', $username)->first();
			if($user){
				$user->otp = $otp;
				$user->save();
				return response()->json([
					'result'=>true, 
					'otp'=>$otp,
					'message' => 'otp send successfully',
				]);
			}else{
				return response()->json([
					'result'=>false, 
					'message' => 'not found',
				]);
			} 
        }elseif(filter_var($request->get('username'), FILTER_VALIDATE_EMAIL)) { 
            $validator_chk = Validator::make($request->all(), [ 
				'username' => 'required|email|exists:users,email'
			]);

			if($validator_chk->fails()){
				return response()->json(['result'=>false,'errors'=>$validator_chk->errors()]); 
			}

			$username = $request->get('username'); 

			$user = User::where('email', $username)->first();
			if($user){
				$user->otp = $otp;
				$user->save();
				return response()->json([
					'result'=>true, 
					'otp'=>$otp,
					'message' => 'otp send successfully',
				]);
			}else{
				return response()->json([
					'result'=>false, 
					'message' => 'not found',
				]);
			}
        }   
	}  
}
