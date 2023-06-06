@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Bookings</li>
			</ol>
		</div>
		<div class="col text-md-end">
		</div>
	</div>
	<div class="row g-3 clearfix row-deck mt-3">
		<div class="col-lg-12 col-md-12">
			<div class="card">
				<div class="card-body">
					<form class="row g-3" id="filter_form">
						<div class="row">
							<div class="col-md-3">
								<label class="form-label">Booking No</label>
								<input type="text" class="form-control" name="booking_no" id="booking_no">
							</div>
							<div class="col-md-3">
								<label class="form-label">Date From</label>
								<input type="date" class="form-control" name="date_from" id="date_from">
							</div>
							<div class="col-md-3">
								<label class="form-label">Date To</label>
								<input type="date" class="form-control" name="date_to" id="date_to">
							</div>
							<div class="col-md-3">
								<label class="form-label">Session</label>
								<select class="form-control" id="session">
									<option value="all" selected>All</option>
									<option value="morning">Morning</option>
									<option value="afternoon">Afternoon</option>
									<option value="evening">Evening</option>
								</select>
							</div>
							<div class="col-md-3">
								<label class="form-label">Services</label>
								<select class="form-control" id="service">
									<option value="all" selected>All</option>
									@foreach ($services as $item)
									<option value="{{$item->id}}">{{$item->service_name}}</option>	
									@endforeach
								</select>
							</div>
							<div class="col-md-3">
								<label class="form-label">Pet</label>
								<select class="form-control" id="pet_id">
									<option value="all" selected>All</option>
									@foreach ($pets as $item)
									<option value="{{$item->id}}">{{$item->pet_name}}</option>	
									@endforeach
								</select>
							</div>
							<div class="col-md-2" style="padding-top:2rem;">
								<label>&nbsp;</label>
								<button  type="button" class="btn bg-primary btn-xs text-white rounded-circle" id="filterBtn"><i class="fa fa-search"></i></button>	
								<button  type="button" class="btn bg-danger btn-xs text-white rounded-circle" id="resetfilterBtn">X</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- start: page body -->
<div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0 mt-lg-1">
	<div class="row g-2 clearfix">
		<div class="col-md-12 mt-4">
			<div class="card">
				<div class="card-body">
					<table id="breed-table" class="table display dataTable table-hover" style="width:100%">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Booking.No</th>
								<th>Date</th>
								<th>Service</th>
								<th>Session</th>
								<th>Pet Name</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
 
@endsection
@section('scripts')
<script type="text/javascript">
var tableX = $('#breed-table').DataTable({
processing:true,
serverSide:true,
ajax: {
  url: "{{url('my-filter-booking') }}",
  data: function (d) {
  	d.booking_no = $("#booking_no").val();
	d.date_from=$('#date_from').val();
	d.date_to=$('#date_to').val();
	d.session=$('#session').val();
	d.service=$('#service').val();
	d.pet_id=$('#pet_id').val();
  } 
},
"columns" : [
// { data : "checkbox", name : "checkbox" },
{ "data": 'DT_RowIndex',
orderable: false,
searchable: false },
{ data : "booking_no", name : "booking_no" },
{ data : "date", name : "date" },
{ data : "service_name", name : "service_name" },
{ data : "session", name : "session" },
{ data : "pet_name", name : "pet_name" },
{ data : "status", name : "status" },
{ data : "action", name : "action"}
],
responsive: true,
"searching": false,
"bStateSave": true,
"bAutoWidth":false,
"ordering": false,
});
$('#filterBtn').on('click',function(){
 tableX.draw();
});
 $('#resetfilterBtn').on('click',function(){
	$('#booking_no').val('');
	$('#date_from').val('');
	$('#date_to').val('');
	$('#session').val('all').trigger('change');
	$('#service').val('all').trigger('change');
	$('#pet_id').val('all').trigger('change');
	tableX.draw();
});

 
 
$(document).on('click','.statusBtn',function(){
	let thisVal=$(this).val();
	let status=$(this).attr('data-status');
	Swal.fire({
		title: 'Are you sure?',
		text: "You won't be able to revert this!",
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Yes,  continue!',
		}).then((data) => {
		if (data.isConfirmed) {
			$.ajax({
				url:"{{url('my-change-booking-status')}}",
				type:'post',
				data:{'_token':"{{csrf_token()}}",'id':thisVal,'status':status},
				success:function(data){
					if(data == '1')
					{
						tableX.draw();
					}
				}
			});
		}
	});
});
</script>
@endsection()