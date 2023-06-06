@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('my-service')}}" aria-current="page">Service</a></li>
				<li class="breadcrumb-item active" aria-current="page">Edit</li>
			</ol>
		</div>
	</div>
</div>
<!-- start: page body -->
<div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-3">
	<div class="container-fluid">
		<div class="row g-4">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="card-header"style="background-color:#ad006066;">
						<h6 class="card-title mb-0"style="color:white">Edit Form</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						<form action="{{route('my-service.update',$service->id)}}" method="post" id="form" enctype="multipart/form-data">
							@csrf
							@method('PUT')
							<div class="form-group col-md-6 mb-3">
								<label> Service Name </label> 
								<select class="form-control" name="service_id" id="service_id"  required>
									<option value="" selected disabled>Choose</option>
									@foreach ($services as $item)
									<option value="{{$item->id}}" @if($service->service_id == $item->id) selected @endif>{{$item->service_name}}</option>	
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Price</label>
								<input type="text" onkeyup="validatePrice(this)" class="form-control" name="price" id="price" placeholder="Price" required value="{{old('price',$service->price)}}">
								@if($errors->has('price'))
								<span class="error text-danger mt-2">{{$errors->first('price')}}</span>
								@endif
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Offer Price</label>
								<input type="text" onkeyup="validatePrice(this)" class="form-control" name="offer_price" id="offer_price" placeholder="Offer Price" value="{{old('offer_price',$service->offer_price)}}">
								@if($errors->has('offer_price'))
								<span class="error text-danger mt-2">{{$errors->first('offer_price')}}</span>
								@endif
							</div>
						</div>
						<div class="col-md-12 mb-3" align="right">
							<div class="col-12">
								<button  type="submit" class="btn btn rounded-4 btn-primary ">Submit</button>
								<a class="btn btn rounded-4 btn-secondary" href="{{ url('my-service') }}">Back</a>
							</div>
						</div>
					</form>
				</div>	
			</div>
		</div>
	</div>
</div>
</div>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js" integrity="sha512-8QFTrG0oeOiyWo/VM9Y8kgxdlCryqhIxVeRpWSezdRRAvarxVtwLnGroJgnVW9/XBRduxO/z1GblzPrMQoeuew==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$('.dropify').dropify();
$('.update-image').on('change',function() {
var update_image =$(this).attr('data-value');
var formData = new FormData($('#form')[0]);
formData.append('update_image',update_image);
formData.append('edit_service_image', this.files[0]);
formData.delete('_method');
$.ajax({
	url:"{{url('my-update-images')}}",
	type:"post",
	data:formData,
	processData: false,
	contentType: false,
	success: function (data) {
		$('#icon'+update_image).attr('src',data);
	}
});
});

$('.delete-button').on('click',function(){
var deleteImage=$(this).val();
Swal.fire({
title: 'Are you sure?',
text: "You won't be able to revert this!",
icon: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Yes,  continue!',
}).then((result) => {
if (result.isConfirmed) {
$.ajax({
	url:"{{url('my-images-delete')}}",
	type:'post',
	data:{'id':deleteImage,"_token":"{{csrf_token()}}"},
	success:function(result){
		$('#delete-container'+deleteImage+' .del_class ').hide();
		$('.update-image').val('');
		console.log(deleteImage);
	}
});
}
});
});  

 
</script>
@endsection