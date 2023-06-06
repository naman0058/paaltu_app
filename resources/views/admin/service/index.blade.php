@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item active" aria-current="page">Services</li>
			</ol>
		</div>
		<div class="col text-md-end">
			<a class="btn btn-primary" href="{{ route('service.create')}}"><i class="fa fa-plus me-2"></i>Add New Service</a>	
		</div>
	</div>
	<div class="row g-3 clearfix row-deck mt-3">
		<div class="col-lg-12 col-md-12">
			<div class="card">
				<div class="card-body">
					<form class="row g-3" id="filter_form" enctype="multipart/formdata">
						<div class="row">
							<div class="col-md-5">
								<label class="form-label">Service Name</label>
								<input type="text" class="form-control" name="service_name" id="service_name">
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
					<table id="service-table" class="table display dataTable table-hover" style="width:100%">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Icon</th>
								<th>Service Name</th>
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
<div class="modal fade bd-example-modal-lg viewModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div id="serviceShow"></div>
	</div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
var tableX = $('#service-table').DataTable({
processing:true,
serverSide:true,
ajax: {
	url: "{{url('filter-service') }}",
	data: function (d) {
		d.service_name = $("#service_name").val()
	}
},
"columns" : [
// { data : "checkbox", name : "checkbox" },
{ "data": 'DT_RowIndex',
orderable: false,
searchable: false },
{ data : "icon", name : "icon" },
{ data : "service_name", name : "service_name" },
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
	$('#service_name').val('');
	tableX.draw();
});

$(document).on('click','.deleteBtn',function(){
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
	url:"{{url('service-delete')}}",
	type:'post',
	data:{'_token':"{{csrf_token()}}",'id':$(this).val()},
	success:function(data){
		if(data == '1')
		{
		tableX.draw();
		}
	}
});
}
})
});
$(document).on('click','.serviceViewBtn',function(){
$('#serviceShow').html('');
$.ajax({
	url:"{{url('serviceview')}}",
	type:"get",
	data:{'_token':"{{csrf_token()}}",'id':$(this).val()},
	success:function(data){
		$('#serviceShow').html(data);
		$('.viewModal').modal('show');
	}
});
});
</script>
@endsection()