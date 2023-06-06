@extends('layouts.adminlayout')
@section('content')
<!-- start: page toolbar -->
<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
	<div class="row g-3 mb-3 align-items-center">
		<div class="col">
			<ol class="breadcrumb bg-transparent mb-0">
				<li class="breadcrumb-item"><a class="text-secondary" href="{{route('dashboard')}}">Dashboard</a></li>
				<li class="breadcrumb-item"> <a class="text-secondary" href="{{url('pet-profile')}}" aria-current="page">Pet Profile</a></li>
				<li class="breadcrumb-item active"  aria-current="page">Create</li>
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
						<h6 class="card-title mb-0"style="color:white">Create Form</h6>
						<div class="dropdown morphing scale-left">
							<a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="" data-bs-original-title="Card Full-Screen" aria-label="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
						</div>
					</div>
					<div class="card-body">
						<form action="{{route('pet-profile.store')}}" method="post"  enctype="multipart/form-data">
							@csrf
							<div class="form-group col-md-6 mb-3">
								<label>Pet Name </label>
								<input type="text" class="form-control" name="pet_name" id="pet_id" placeholder="Pet Name"required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Pet Category </label>
								<select class="array-select form-control form-select" name="pet_category_id" placeholder="Pet Category" required>
									<option value="">Choose Pet Category</option>
									@foreach($pets as $pet)
									<option value="{{$pet->id}}" {{ old('pet_category_id')== $pet->id ? 'selected': ''}}>{{$pet->name}}
									</option>
									@endforeach()
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label> Breed </label>
								<select class="array-select form-control form-select" name="breed_id" required>
									<option value="">Choose Breed</option>
									@foreach($breeds as $breed)
									<option value="{{$breed->id}}" {{ old('breed_id')== $breed->id ? 'selected': ''}}>{{$breed->breed_name}}
									</option>
									@endforeach()
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Date Of Birth </label>
								<input type="date" class="form-control" name="date_of_birth" id="date_of_birth" placeholder="Date Of Birth"required>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>Gender </label>
								<select  class="array-select form-control form-select" name="gender" id="gender_id"required>
									<option>Choose Gender</option>
									<option value="male" >Male</option>
									<option value="female">Female</option>
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>User </label>
								<select  class="array-select form-control form-select" name="user_id" id="user_id" required>
									<option>Choose </option>
									@foreach ($users as $user)
									<option value="{{$user->id}}" >{{$user->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-6 mb-3">
								<label>About/Bio</label>
								<textarea class="form-control" name="about" id="about" required></textarea>
							</div>
							<h5 style="padding-top: 5px;">Add Images</h5>
							<div class="row">	
								<div class="col-md-4 mb-3">
									<input type="file" name="pet_image[]" placeholder="File" class="form-control" required>
								</div>
								<div class="col-md-2 mb-3">
									<div class="form-check">
										<input class="form-check-input" type="radio" name="is_default" id="flexRadioDefault0"  value="1" checked>
									</div>
								</div>
								<div class="col-md-2 mb-3">
									<button class="btn btn-primary" type="button" id="addFileBtn">+</button>
								</div>
							</div>
							<div id="appendImages"></div>
							<input type="hidden" name="hiddenVal" id="hiddenVal">
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
var c=1;
var dt = [1];
$(document).on('click','#addFileBtn',function(){
c++;
dt.push(c);
var template='<div class="row" id="row_'+c+'"><div class="col-md-4 mb-3"><input type="file" class="form-control" name="pet_image[]" placeholder="File"></div><div class="col-md-2 mb-3"> <div class="form-check"><input class="form-check-input" type="radio" name="is_default" id="flexRadioDefault'+c+'" value="'+c+'"></div></div><div class="col-md-2  mb-3"><button type="button"class="btn btn-danger removeClass" value="'+c+'">-</button></div></div>';
$('#hiddenVal').val(dt.join());
$('#appendImages').append(template);
});
var new_id=[];
$(document).on('click','.removeClass',function(){
var thisId=$(this).val();
new_id.push(thisId);
const index =new_id.indexOf(thisId);
if (index > -1) {
	for( var i = 0; i < dt.length; i++){ 
  if ( dt[i] == thisId) { 
 		dt.splice(i,1);
  }
  }
  }
$('#hiddenVal').val(dt.join()); 
$('#row_'+thisId).remove();
});
</script>
@endsection()