@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('pet-profile')}}" aria-current="page">Pet Profile</a></li>
				
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
						<form action="{{route('pet-profile.update',$profiles->id)}}" method="post" id="petupdateform" enctype="multipart/form-data">
							@csrf
							@method('PUT')
							
							<div class="form-group col-md-6 mb-3">
								<label> Pet Name </label>
								<input type="text" value="{{$profiles->pet_name}}" class="form-control" name="pet_name" id="pet_id" placeholder="Pet Name" required>
							</div>
							
							<div class="form-group col-md-6 mb-3">
								<label>Date Of Birth </label>
								<input type="date" value="{{$profiles->date_of_birth}}" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="Date Of Birth" required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Pet Category </label>
								<select class="array-select form-control form-select" name="pet_category_id" placeholder="Pet Category" required>
									<option value="">Choose Pet Category</option>
									@foreach($pets as $pet)
									<option value="{{$pet->id}}" @if($pet->id == $profiles->pet_category_id) selected @endif>{{$pet->name}}
									</option>
									@endforeach()
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Breed </label>
								<select class="array-select form-control form-select" name="breed_id" placeholder="Breed" required>
									<option value="">Select Breed</option>
									@foreach($breeds as $breed)
									<option value="{{$breed->id}}" @if($breed->id == $profiles->breed_id) selected @endif>{{$breed->breed_name}}
									</option>
									@endforeach()
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Gender</label>
								<select  class="array-select form-control form-select" name="gender" required>
									<option @if('male' == $profiles->gender) selected @endif>male</option>
									<option @if('female' == $profiles->gender) selected @endif>female</option></select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>User </label>
								<select  class="array-select form-control form-select" name="user_id" id="user_id" required>
									<option>Choose </option>
									@foreach ($users as $user)
									<option value="{{$user->id}}" {{$user->id == $profiles->user_id ? 'selected' : ''}}>{{$user->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>About/Bio</label>
								<textarea class="form-control" name="about" id="about" required>{{$profiles->about}}</textarea>
							</div>
								<h4 style="padding-top: 5px;">Add Image &nbsp;<button class="btn btn-primary" id="addFileBtn" type="button" >+</button></h4>
								<div class="row" style="padding-bottom: 5px;">
									<div class="col-md-4"></div>
									<div class="col-md-8"> </div>
									<input type="hidden" name="countvariable" id="countvariable" value="{{$item_count}}">
									<input type="hidden" name="image_edit_id" id="image_edit_idset">
									<input type="hidden" name="radio_id" id="default_radio_id">
								</div>
								@if($item_count>0)
								@php $c=0; @endphp
								@foreach($petitems as $row)
								@php
								$c++;
								@endphp
								<div class="row" id="img_{{$row->id}}">
									<input type="hidden" name="rowid[]" value="{{$row->id}}">
									<div class="col-md-3 mb-3">
										<input type="file" name="pet_image_edit[]" placeholder="Image" class="form-control imgedit" id="petEditId_{{$row->id}}" >
									</div>
									<div class="col-md-2 mb-3">
										<img src="{{ asset('public/images/pet/files')}}/{{ $row->image}}" style="width:50px;height:50px;" id="{{$row->id}}">
									</div>
									<div class="col-md-2 mb-3">
										<div class="form-check">
											<input class="form-check-input radiocheck" type="radio" name="is_default" id="flexRadioDefault{{$c}}"  value="{{$row->id}}" {{ $row->is_default=='yes'? 'checked': ''}}>
										</div>
									</div>
									<div class="col-md-2 mb-3">
										<button type="button"class="btn btn-danger deleteImg" id="btn_id_{{$row->id}}"value="{{$row->id}}"><i class="fa fa-trash"></i></button>
									</div>
								</div>
								@endforeach
								@endif
								<div id="appendImages"></div>
								<input type="hidden" name="hiddenVal" id="hiddenVal">
								<input type="hidden" name="imagesvalue" id="imagesvalue">
								<input type="hidden" name="imagesvalue1" id="imagesvalue1">
								<div class="col-md-12 mb-3" align="right">
									<div class="col-12">
										<button  type="submit" class="btn btn rounded-4 btn-primary">Submit</button>
										<a class="btn btn rounded-4 btn-secondary" href="{{ url('pet-profile') }}">Back</a>
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
	<script>
	var arrays=[];
	var arrays1=[];
	$('.radiocheck').each(function(){
		arrays.push($(this).val());
		});
	$('#imagesvalue').val(arrays);
	$('#imagesvalue1').val(arrays1);
	var c="{{ time() }}";
	
	$(document).on('click','#addFileBtn',function(){
	c++;
	arrays.push(c);
	arrays1.push(c);
	$('#imagesvalue').val(arrays);
	$('#imagesvalue1').val(arrays1);
	var template='<div class="row" id="row_'+c+'"><div class="col-md-3 mb-3"><input type="file" class="form-control" name="pet_image[]" placeholder="Image"></div><div class="col-md-2 mb-3"></div><div class="col-md-2 mb-3"> <div class="form-check"><input class="form-check-input" type="radio" name="is_default" id="flexRadioDefault'+c+'" value="'+c+'"></div></div><div class="col-md-2  mb-3"><button type="button"class="btn btn-danger removeClass" value="'+c+'">-</button></div></div>';
	
	$('#appendImages').append(template);
	});
	var new_id=[];
	$(document).on('click','.removeClass',function(){
	var thisId=$(this).val();
	new_id.push(thisId);
	const index =new_id.indexOf(thisId);
	if (index > -1) {
	for( var i = 0; i < arrays.length; i++){
	if ( arrays[i] == thisId) {
	arrays.splice(i,1);
	}
	}
	}
	
	$('#row_'+thisId).remove();
	$('#imagesvalue').val(arrays);
	$('#imagesvalue1').val(arrays1);
	});

	// delete-images(append-images)
	$('.deleteImg').on('click',function(){
	var image_row=$(this).val();
	var parent_row_id=$('#btn_id_'+image_row).parent().parent().attr('id');
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
		url:  "{{ url('removeImage') }}",
		type: "POST",
		data: {'image_row':image_row,'_token': '{{ csrf_token() }}'},
		success: function (data)
	{
	$('#img_'+image_row).remove();
	new_id.push(parent_row_id);
	const index =new_id.indexOf(parent_row_id);
	if (index > -1) {
	for( var i = 0; i < dt.length; i++){
	if ( dt[i] == parent_row_id) {
	dt.splice(i,1);
	}
	}
	}
	$('#hiddenVal').val(dt.join());
	$('#'+parent_row_id).remove();
	}
	});
	}
	})
	});
	var image_edit_id=[];
	$('.imgedit').on('change',function(){
	var s_id=$(this).attr('id');
	var s_id_split=s_id.split('_');
	image_edit_id.push(s_id_split[1]);
	$('#image_edit_idset').val(image_edit_id);
	});
 
	$(document).on('change','.radiocheck',function(){
	var radioId=$(this).val();
	var edit_radio_id=$(this).attr('id').split('_');
	$('#default_radio_id').val(radioId);
	$('#default_radio_rowId').val(edit_radio_id[1]);
	});

	</script>
	@endsection()