<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\PetProfile;
use App\Models\Service;
use App\Models\Vendor;
use DataTables;

class BookingController extends Controller
{
    public function index()
    {
		$services=Service::get();
		$vendors=Vendor::get();
		$pets=PetProfile::get();
        return view('admin.booking.index',[
			'pets'=>$pets,
			'vendors'=>$vendors,
			'services'=>$services,
		]);
    }
   
    public function filter(Request $request)
    {
        $data = Booking::select('bookings.*','services.user_id as s_user_id','services.vendor_id')->with(['service','user'])
		->leftJoin('services','services.id','bookings.service_id');
         

        return Datatables::of($data)
				->filter(function($data)use($request){
					if($request->has('booking_no') && !empty($request->booking_no))
					{
						$data=$data->where('booking_no','like','%'.$request->booking_no.'%');
					}
					if($request->has('pet_id') && $request->pet_id != 'all')
					{
						$pet=PetProfile::find($request->pet_id);
						$data=$data->where('pet_user_id',$pet->user_id);
					}
					if($request->has('session') && $request->session != 'all')
					{
						$data=$data->where('session',$request->session);
					} 
					if($request->has('date_from') && $request->date_from != '')
					{
						$data=$data->whereDate('date','>=',$request->date_from);
					}
					if($request->has('date_to') && $request->date_to != '')
					{
						$data=$data->whereDate('date','<=',$request->date_to);
					}
					if($request->has('vendor') && $request->vendor != 'all')
					{
						$data=$data->where('vendor_id',$request->vendor);
					}
					
				})
                ->addIndexColumn()
				->addColumn('service_name',function($data){
					return isset($data->service) && isset($data->service->service_name)? $data->service->service_name: 'N/A'; 
				})
				->addColumn('vendor_name',function($data){
					return Vendor::getVendorName($data->service_id);
				})
                ->editColumn('date', function($data) { return date('Y-m-d',strtotime($data->date)); })
                ->addColumn('pet_name', function($data) { return PetProfile::getNameByUserId($data->pet_user_id); })
                ->editColumn('status', function($data) { 
					if($data->status == 'pending')
					{
						return "<span class='text-dark' >Pending</span>";
					}
					if($data->status == 'accepted')
					{
						return "<span class='text-success' >Accepted</span>";
					}
					if($data->status == 'rejected')
					{
						return "<span class='text-danger' >Rejected</span>";
					}
					if($data->status == 'completed')
					{
						return "<span class='text-primary' >Completed</span>";
					}
					 
				 })
                ->rawColumns(['date','action','status'])
                ->addColumn('action',function($data){
                    if($data->status == 'pending')
					{
						return "<button type='button' class='btn btn-danger statusBtn' data-status='rejected' value=".$data->id.">Reject</button>
								<button type='button' class='btn btn-success statusBtn'  data-status='accepted' value=".$data->id.">Accept</button>";
					}
					if($data->status == 'accepted')
					{
						return "<button type='button' class='btn btn-primary statusBtn'  data-status='completed' value=".$data->id.">Complete</button>";
					}
					if($data->status == 'rejected')
					{
						return "<button type='button' class='btn btn-danger'>Rejected</button>";
					}
					return "<button type='button' class='btn btn-secondary'>Completed</button>" ;
                })
                ->setRowId(function ($data) {
                return "row_".$data->id;
                })
                ->make(true);
    }
    public function changeBookingStatus(Request $request)
	{
		$data=Booking::find($request->id);
		$data->status=$request->status;
		if($data->save())
		{
			echo 1;exit;
		}
 		echo 0;exit;

	}
    
}


   